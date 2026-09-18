# Implementation Plan: Authentication

## Overview

Implementación del módulo de autenticación, gestión de usuarios y control de acceso por roles de LiveSales. Cubre la instalación y configuración de Spatie Laravel Permission, el modelo `User` extendido con `is_active`, la autenticación nativa de Laravel 12 con rate limiting, el middleware de estado activo, los controladores y vistas de login y gestión de usuarios, las acciones de negocio con concurrencia, los seeders iniciales y los Feature Tests que verifican las 8 Correctness Properties del diseño.

No se utiliza ningún starter kit (Breeze, Jetstream, etc.). Todo el código se escribe desde cero siguiendo las convenciones de Laravel 12.

---

## Tasks

- [ ] 1. Instalar Spatie Laravel Permission y preparar la configuración base
  - [ ] 1.1 Instalar `spatie/laravel-permission` vía Composer y publicar sus migraciones
    - Ejecutar `composer require spatie/laravel-permission`
    - Publicar la migración de Spatie con `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
    - Verificar que las migraciones de Spatie aparezcan en `database/migrations/`
    - _Requirements: 6.1_

  - [ ] 1.2 Crear el archivo de configuración `config/livesales.php`
    - Crear `config/livesales.php` que exponga `admin_name`, `admin_email` y `admin_password` leídos desde `env('ADMIN_NAME')`, `env('ADMIN_EMAIL')` y `env('ADMIN_PASSWORD')` respectivamente
    - Añadir `ADMIN_NAME`, `ADMIN_EMAIL` y `ADMIN_PASSWORD` a `.env.example` con valores de ejemplo vacíos o descriptivos
    - Nunca llamar a `env()` directamente desde los seeders; toda lectura pasa por `config()`
    - _Requirements: 9.2, 9.5_

- [ ] 2. Extender la base de datos para el campo `is_active` y actualizar el modelo `User`
  - [ ] 2.1 Crear la migración para añadir `is_active` a la tabla `users`
    - Crear una nueva migración (no modificar la migración original de Laravel) que añada `$table->boolean('is_active')->default(true)->index()` a la tabla `users`
    - El método `down()` debe eliminar la columna con `dropColumn('is_active')`
    - _Requirements: 5.1, 5.2, 8.4_

  - [ ] 2.2 Actualizar el modelo `App\Models\User`
    - Añadir el trait `HasRoles` de Spatie
    - Añadir `'is_active'` al array `$fillable`
    - Añadir el cast `'is_active' => 'boolean'` en el método `casts()`
    - Añadir `protected $attributes = ['is_active' => true]` para el valor por defecto
    - _Requirements: 4.11, 5.1, 6.1_

- [ ] 3. Crear el middleware `EnsureUserIsActive` y registrar los aliases en `bootstrap/app.php`
  - [ ] 3.1 Crear `App\Http\Middleware\EnsureUserIsActive`
    - Implementar el método `handle`: si `Auth::check()` y `Auth::user()->is_active === false`, ejecutar `Auth::logout()`, `$request->session()->invalidate()`, `$request->session()->regenerateToken()` y redirigir a la ruta `login` con error `'email' => 'Tu cuenta está desactivada.'`
    - Si el usuario está activo (o no hay sesión), llamar a `$next($request)`
    - _Requirements: 3.3, 3.4, 5.3_

  - [ ] 3.2 Registrar aliases de middleware en `bootstrap/app.php`
    - En el closure `withMiddleware`, usar `$middleware->alias([...])` para registrar:
      - `'active'` → `App\Http\Middleware\EnsureUserIsActive::class`
      - `'role'` → `Spatie\Permission\Middleware\RoleMiddleware::class`
    - _Requirements: 3.1, 3.5, 7.1_

- [ ] 4. Implementar la autenticación: `LoginRequest` y `AuthenticatedSessionController`
  - [ ] 4.1 Crear `App\Http\Requests\Auth\LoginRequest`
    - Crear el Form Request con reglas de validación: `email` (required, string, email format) y `password` (required, string)
    - Implementar el método público `authenticate()` que centraliza toda la lógica de throttle y autenticación:
      - Clave de throttle: `sha1($this->input('email').'|'.$this->ip())`
      - Comprobar `RateLimiter::tooManyAttempts($key, 5)` antes del intento; si está bloqueado, lanzar `ValidationException` con el mensaje de segundos restantes
      - Ejecutar `Auth::attempt(['email' => $this->email, 'password' => $this->password])` sin incluir `is_active` en las credenciales
      - Si falla: incrementar `RateLimiter::hit($key)` y lanzar `ValidationException` con mensaje genérico que no indique cuál campo es incorrecto
      - Si tiene éxito: limpiar `RateLimiter::clear($key)` y retornar (la sesión queda abierta; la comprobación de `is_active` es responsabilidad del controlador)
    - _Requirements: 1.2, 1.3, 1.8, 1.9, 1.10_

  - [ ] 4.2 Crear `App\Http\Controllers\Auth\AuthenticatedSessionController`
    - Método `create()`: retorna la vista `auth.login`
    - Método `store(LoginRequest $request)`:
      - Llamar `$request->authenticate()` una única vez — toda la lógica de throttle y el intento de autenticación ocurre dentro de `LoginRequest`
      - Si `authenticate()` lanza `ValidationException`, Laravel lo maneja automáticamente y redirige al formulario con errores; el controlador no necesita capturarla
      - Si `authenticate()` retorna sin excepción, la sesión ya está abierta; comprobar inmediatamente `Auth::user()->is_active`
      - Si `is_active` es `false`: llamar `Auth::logout()`, `$request->session()->invalidate()`, `$request->session()->regenerateToken()` y retornar error `'Tu cuenta está desactivada.'`
      - Si `is_active` es `true`: llamar `$request->session()->regenerate()` y redirigir al dashboard
      - No ejecutar un segundo `Auth::attempt()` dentro del controlador
    - Método `destroy(Request $request)`:
      - `Auth::logout()`, `$request->session()->invalidate()`, `$request->session()->regenerateToken()`
      - Redirigir a la ruta `login`
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.6, 2.1, 2.2_

- [ ] 5. Implementar `DeactivateUserAction` con protección del último Administrador activo
  - [ ] 5.1 Crear `App\Actions\Users\DeactivateUserAction`
    - Método público `execute(User $user): void`
    - Envolver la lógica completa en `DB::transaction()`
    - Dentro de la transacción:
      - Obtener todos los usuarios con rol `Administrador` e `is_active = true` usando `lockForUpdate()` (bloqueo pesimista)
      - Filtrar excluyendo al usuario objetivo
      - Si `count === 0`, lanzar una excepción (o retornar error) con el mensaje `'No es posible realizar esta operación porque dejaría al sistema sin administradores activos.'`
      - Si `count > 0`, establecer `$user->is_active = false` y llamar `$user->save()`
    - _Requirements: 5.4, 5.5_

- [ ] 6. Implementar `StoreUserRequest`, `UpdateUserRequest` y `UserController`
  - [ ] 6.1 Crear `App\Http\Requests\StoreUserRequest`
    - Reglas: `name` (required, string, max:255), `email` (required, email, max:255, unique:users), `password` (required, string, min:8, max:255, confirmed), `role` (required, in:Administrador,Vendedor)
    - _Requirements: 4.2, 4.3, 4.4, 6.2, 6.7_

  - [ ] 6.2 Crear `App\Http\Requests\UpdateUserRequest`
    - Reglas: `name` (required, string, max:255), `email` (required, email, max:255, unique:users,email,{id} usando `ignore($this->route('user'))`), `password` (nullable, string, min:8, max:255, confirmed), `role` (required, in:Administrador,Vendedor)
    - _Requirements: 4.5, 4.6, 4.7, 4.8_

  - [ ] 6.3 Crear `App\Http\Controllers\UserController`
    - `index()`: obtener usuarios paginados (25 por página) con `->paginate(25)` e incluir eager loading del rol; retornar vista `users.index`
    - `create()`: retornar vista `users.create`
    - `store(StoreUserRequest $request)`: dentro de `DB::transaction()`, crear usuario con `User::create([...])` (is_active = true por defecto) y asignar rol con `$user->assignRole($request->validated('role'))`; la transacción garantiza que no exista un usuario sin rol si la asignación falla; redirigir a `users.index` con mensaje de éxito
    - `edit(User $user)`: retornar vista `users.edit` con el usuario
    - `update(UpdateUserRequest $request, User $user)`: actualizar nombre y email; actualizar contraseña solo si fue proporcionada; si el usuario tenía rol `Administrador` y el nuevo rol es `Vendedor`, envolver en `DB::transaction()` con `lockForUpdate()` para verificar que quede al menos un Administrador activo; aplicar `$user->syncRoles([$request->validated('role')])` dentro de la misma transacción; redirigir a `users.index` con mensaje de éxito o error
    - `activate(User $user)`: establecer `is_active = true`, guardar, redirigir con mensaje de éxito
    - `deactivate(User $user)`: delegar a `DeactivateUserAction::execute($user)`, capturar excepción en caso de último Administrador, redirigir con mensaje de éxito o error
    - _Requirements: 4.1, 4.2, 4.5, 4.11, 5.1, 5.2, 5.6, 6.3, 6.8_

- [ ] 7. Definir las rutas en `routes/web.php`
  - [ ] 7.1 Registrar todas las rutas del módulo de autenticación
    - Rutas públicas bajo `middleware('guest')`: `GET /login` y `POST /login` → `AuthenticatedSessionController`
    - Ruta de logout: `POST /logout` bajo `middleware('auth')` → `AuthenticatedSessionController::destroy`
    - Grupo interno bajo `middleware(['auth', 'active'])`:
      - `GET /dashboard` → vista `dashboard` (accesible para todos los roles autenticados)
      - Subgrupo bajo `middleware('role:Administrador')` con prefijo `users` y nombre `users.`:
        - `GET /` → `UserController@index`
        - `GET /create` → `UserController@create`
        - `POST /` → `UserController@store`
        - `GET /{user}/edit` → `UserController@edit`
        - `PUT /{user}` → `UserController@update`
        - `PATCH /{user}/activate` → `UserController@activate`
        - `PATCH /{user}/deactivate` → `UserController@deactivate`
    - _Requirements: 1.5, 2.2, 3.1, 3.2, 7.1, 7.2_

- [ ] 8. Configurar Bootstrap 5 vía npm y Vite
  - [ ] 8.1 Instalar Bootstrap 5 y sus dependencias mediante npm
    - Ejecutar `npm install bootstrap @popperjs/core`
    - Verificar que `bootstrap` y `@popperjs/core` aparecen en `package.json`
    - _Requirements: (configuración de frontend)_

  - [ ] 8.2 Importar Bootstrap en los assets de Laravel
    - En `resources/js/app.js`, añadir `import 'bootstrap'`
    - En `resources/css/app.css` (o en un archivo SCSS si corresponde), añadir `@import 'bootstrap/dist/css/bootstrap.min.css'` o la importación equivalente vía Sass si el proyecto lo soporta
    - No utilizar CDN en ninguna vista Blade
    - _Requirements: (configuración de frontend)_

  - [ ] 8.3 Configurar Vite y el paginador de Bootstrap
    - Verificar que `vite.config.js` incluye los assets `resources/js/app.js` y `resources/css/app.css` en la configuración de `laravel()`
    - Ejecutar `npm run build` y verificar que la compilación termina sin errores
    - En `App\Providers\AppServiceProvider::boot()`, añadir `\Illuminate\Pagination\Paginator::useBootstrapFive()` para que `{{ $users->links() }}` genere HTML compatible con Bootstrap 5
    - Los layouts Blade deben cargar los assets compilados usando `@vite(['resources/css/app.css', 'resources/js/app.js'])` en lugar de tags `<link>` o `<script>` manuales apuntando a CDN
    - _Requirements: 4.1_

- [ ] 10. Crear las vistas Blade con Bootstrap
  - [ ] 10.1 Crear el layout principal `resources/views/layouts/app.blade.php`
    - Layout para usuarios autenticados con Bootstrap 5
    - Incluir navegación superior con el nombre del usuario, su rol y enlace/botón de logout (con formulario POST y token CSRF)
    - Incluir un área `@yield('content')` o `{{ $slot }}` para el contenido de cada página
    - _Requirements: 2.1, 2.2_

  - [ ] 10.2 Crear el layout de invitado `resources/views/layouts/guest.blade.php`
    - Layout limpio sin navegación para la página de login
    - Incluir Bootstrap 5 y un contenedor centrado
    - _Requirements: 1.1_

  - [ ] 10.3 Crear la vista de login `resources/views/auth/login.blade.php`
    - Extender `layouts.guest`
    - Formulario con `method="POST"`, `action="{{ route('login') }}"` y `@csrf`
    - Campos: `email` (type="email") y `password` (type="password")
    - Mostrar errores de validación usando `$errors`
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.8, 1.9, 1.10_

  - [ ] 10.4 Crear la vista de lista de usuarios `resources/views/users/index.blade.php`
    - Extender `layouts.app`
    - Tabla con columnas: nombre, correo electrónico, rol, estado (activo/inactivo)
    - Botones de acción: editar, activar/desactivar (con formularios POST/PATCH y token CSRF)
    - Enlace para crear nuevo usuario
    - Paginación con `{{ $users->links() }}`
    - Mostrar mensajes de éxito o error de la sesión flash
    - _Requirements: 4.1_

  - [ ] 10.5 Crear la vista de creación de usuario `resources/views/users/create.blade.php`
    - Extender `layouts.app`
    - Formulario con campos: nombre, correo electrónico, contraseña, confirmación de contraseña, rol (select con opciones Administrador/Vendedor)
    - Token CSRF, método POST
    - Mostrar errores de validación por campo
    - _Requirements: 4.2, 6.2_

  - [ ] 10.6 Crear la vista de edición de usuario `resources/views/users/edit.blade.php`
    - Extender `layouts.app`
    - Formulario con `method="POST"`, `@method('PUT')` y `@csrf`
    - Campos pre-rellenados: nombre, correo electrónico, rol (select)
    - Campo contraseña opcional con nota aclaratoria de que dejar vacío conserva la contraseña actual
    - Mostrar errores de validación por campo
    - _Requirements: 4.5, 4.7, 4.8_

- [ ] 11. Crear los seeders iniciales y actualizar `UserFactory`
  - [ ] 11.1 Crear `Database\Seeders\RolesAndPermissionsSeeder`
    - Usar `Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web'])` y lo mismo para `Vendedor`
    - Usar `app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions()` al inicio para limpiar la caché
    - _Requirements: 9.1, 9.4_

  - [ ] 11.2 Crear `Database\Seeders\AdminUserSeeder`
    - Leer `config('livesales.admin_name')`, `config('livesales.admin_email')`, `config('livesales.admin_password')`
    - Si alguno es `null` o vacío, lanzar `\RuntimeException` con mensaje descriptivo antes de crear ningún registro
    - Crear el usuario con `User::firstOrCreate(['email' => $email], ['name' => $name, 'password' => Hash::make($password), 'is_active' => true])`
    - Asignar el rol `Administrador` con `$user->syncRoles(['Administrador'])`
    - _Requirements: 9.2, 9.3, 9.4, 9.5_

  - [ ] 11.3 Actualizar `Database\Seeders\DatabaseSeeder`
    - Reemplazar el contenido existente (que crea un usuario de prueba) por la llamada ordenada: `$this->call([RolesAndPermissionsSeeder::class, AdminUserSeeder::class])`
    - _Requirements: 9.1, 9.4_

  - [ ] 11.4 Actualizar `Database\Factories\UserFactory`
    - Añadir `'is_active' => true` al array de atributos por defecto en el método `definition()`
    - Añadir un estado `inactive()` que retorna `['is_active' => false]` para su uso en los tests
    - _Requirements: 5.1, 5.2_

- [ ] 12. Checkpoint — Verificar estructura base antes de tests
  - Asegurarse de que todas las migraciones estén en orden correcto (usuarios → Spatie → is_active)
  - Aplicar migraciones en la base de datos de desarrollo con `php artisan migrate --seed` (no usar `migrate:fresh` sobre la BD de desarrollo sin confirmación explícita, ya que elimina todos los datos existentes)
  - Para un entorno limpio de prueba puede usarse `php artisan migrate:fresh --seed` sobre una base de datos de testing descartable, no sobre la de desarrollo
  - Verificar que la ruta `/login` carga correctamente con Bootstrap visible y las rutas internas redirigen al login sin sesión
  - Ejecutar `npm run build` para asegurarse de que los assets de Bootstrap se compilan correctamente
  - Consultar al usuario si hay dudas antes de continuar con los tests

- [ ] 13. Escribir Feature Tests para las Correctness Properties
  - [ ] 13.1 Crear `tests/Feature/Authentication/LoginTest.php`
    - Cubrir criterios 1.1–1.4, 1.8–1.10
    - Test: autenticación exitosa redirige al dashboard (Property 1)
    - Test: credenciales incorrectas retornan error genérico sin indicar qué campo falló
    - Test: usuario inactivo no puede iniciar sesión y la sesión no queda activa (Property 1, 2)
    - Test: rate limiting bloquea tras 5 intentos fallidos (criterio 1.10)
    - Test: campos vacíos retornan errores de validación (criterios 1.8, 1.9)
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.8, 1.9, 1.10_

  - [ ] 13.2 Crear `tests/Feature/Authentication/LogoutTest.php`
    - Test: logout invalida la sesión y redirige al login (Property 1)
    - Test: tras logout, intentar acceder a ruta protegida redirige al login
    - _Requirements: 2.1, 2.3_

  - [ ] 13.3 Crear `tests/Feature/Authentication/RouteProtectionTest.php`
    - Usar `#[DataProvider]` con todas las rutas internas para verificar que sin sesión redirigen a `/login`
    - Test: usuario con sesión activa que es desactivado queda desautenticado en la siguiente solicitud (Property 1)
    - Test: usuario inactivo con sesión activa es redirigido al login por `EnsureUserIsActive`
    - _Requirements: 1.5, 3.1, 3.2, 3.3, 3.4_

  - [ ] 13.4 Crear `tests/Feature/Authentication/UserManagementTest.php`
    - Test: listado paginado muestra 25 usuarios por página
    - Test: creación exitosa establece `is_active = true` y asigna el rol correcto (Property 5)
    - Test: contraseña se almacena como hash y nunca en texto plano (Property 2)
    - Test: edición sin nueva contraseña conserva la contraseña existente (Property 2)
    - Test: email duplicado al crear retorna error de validación (criterio 4.3)
    - Test: email duplicado al editar retorna error de validación (criterio 4.6)
    - Test: `created_at` no cambia tras editar el usuario (Property 8)
    - Test: campos obligatorios ausentes o inválidos al crear retornan redirección con `assertSessionHasErrors` y no crean ningún registro en la base de datos (Property 6) — usar `#[DataProvider]` con combinaciones de campos ausentes/inválidos
    - Test: cambio de rol de Administrador a Vendedor aplica protección del último Administrador activo (Property 4)
    - _Requirements: 4.1, 4.2, 4.3, 4.5, 4.6, 4.7, 4.8, 4.9, 4.10, 4.11, 8.2, 8.3_

  - [ ] 13.5 Crear `tests/Feature/Authentication/UserActivationTest.php`
    - Test: desactivación rechazada cuando el usuario es el único Administrador activo (Property 4)
    - Test: desactivación exitosa cuando existen otros administradores activos
    - Test: activación de usuario inactivo lo permite iniciar sesión (criterio 5.2)
    - Test: usuario no encontrado retorna 404 (criterio 5.6)
    - Los tests normales verifican que la **regla de negocio** es respetada: la desactivación es rechazada cuando quedaría cero administradores activos
    - Anotar con `@group mysql` los tests que verifican que la operación usa `DB::transaction()` y `lockForUpdate()` correctamente; estos tests deben ejecutarse sobre MySQL porque SQLite no implementa bloqueos de fila reales
    - No afirmar que un Feature Test secuencial simula una carrera concurrente real; las pruebas paralelas quedan fuera del MVP
    - _Requirements: 5.1, 5.2, 5.4, 5.5, 5.6_

  - [ ] 13.6 Crear `tests/Feature/Authentication/RoleAssignmentTest.php`
    - Test: cada usuario tiene exactamente un rol del conjunto {Administrador, Vendedor} (Property 5)
    - Test: Vendedor recibe HTTP 403 en todos los endpoints de gestión de usuarios — usar `#[DataProvider]` con GET index, GET create, POST store, GET edit, PUT update, PATCH activate, PATCH deactivate (Property 3)
    - Test: crear usuario sin rol válido retorna redirección con `assertSessionHasErrors('role')` y no crea ningún registro en la base de datos (Property 6, criterio 6.7)
    - _Requirements: 6.1, 6.2, 6.4, 6.5, 6.6, 7.1, 7.2, 7.3, 7.4_

  - [ ] 13.7 Crear `tests/Feature/Authentication/SeederTest.php`
    - Test: ejecutar el seeder dos veces no crea duplicados de roles ni del usuario administrador (Property 7)
    - Test: seeder lanza `RuntimeException` cuando las variables de entorno están vacías (criterio 9.5)
    - Test: `created_at` y `updated_at` del usuario administrador no son nulos (Property 8)
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_

- [ ] 14. Checkpoint final — Asegurarse de que todos los tests pasen
  - Ejecutar `php artisan test tests/Feature/Authentication/` y verificar que todos los tests pasan
  - Verificar que no existen consultas N+1 en el listado de usuarios (usar eager loading del rol)
  - Consultar al usuario si hay dudas o ajustes necesarios

---

## Notes

- Las tareas marcadas con `*` son opcionales y pueden omitirse para un MVP más rápido; las tareas de test en este módulo se consideran parte del MVP porque las Correctness Properties cubren reglas de negocio críticas (concurrencia, último Administrador, seguridad de contraseñas)
- Los tests de concurrencia con `lockForUpdate` (Property 4) deben ejecutarse sobre MySQL, no sobre SQLite en memoria, ya que SQLite no implementa bloqueos de fila de la misma forma
- Nunca llamar a `env()` directamente en los seeders; toda lectura pasa por `config('livesales.*')`
- El alias `'role'` en `bootstrap/app.php` debe apuntar a `Spatie\Permission\Middleware\RoleMiddleware::class`; el nombre exacto puede variar según la versión instalada por Composer — verificar al instalar
- No se genera ninguna ruta de registro público ni de reset de contraseña en el MVP
- El campo `email_verified_at` se mantiene en la tabla `users` (viene de la migración original de Laravel) pero no se usa activamente en el MVP
- Cada tarea referencia requisitos específicos para trazabilidad completa con el documento de requirements

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.2"] },
    { "id": 1, "tasks": ["2.1"] },
    { "id": 2, "tasks": ["2.2"] },
    { "id": 3, "tasks": ["3.1", "3.2"] },
    { "id": 4, "tasks": ["4.1"] },
    { "id": 5, "tasks": ["4.2", "5.1"] },
    { "id": 6, "tasks": ["6.1", "6.2"] },
    { "id": 7, "tasks": ["6.3"] },
    { "id": 8, "tasks": ["7.1"] },
    { "id": 9, "tasks": ["8.1", "8.2"] },
    { "id": 10, "tasks": ["8.3"] },
    { "id": 11, "tasks": ["8.4", "8.5", "8.6"] },
    { "id": 12, "tasks": ["9.1"] },
    { "id": 13, "tasks": ["9.2", "9.4"] },
    { "id": 14, "tasks": ["9.3"] },
    { "id": 15, "tasks": ["11.1", "11.2", "11.3"] },
    { "id": 16, "tasks": ["11.4", "11.5", "11.6"] },
    { "id": 17, "tasks": ["11.7"] }
  ]
}
```
