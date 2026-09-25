# Implementation Plan - Customer Management

## Task 1 - Customer domain foundation

- [ ] Crear modelo `Customer`.
- [ ] Crear migración `customers`.
- [ ] Definir:
  - `name`
  - `tiktok_username`
  - `whatsapp`
  - timestamps
- [ ] Crear índices UNIQUE para TikTok y WhatsApp.
- [ ] Crear índice para name.
- [ ] Configurar `$fillable`.
- [ ] Ejecutar migración.
- [ ] Verificar con `migrate:status`.

Requirements:
3, 5, 6, 7, 13, 14

---

## Task 2 - Customer Factory

- [ ] Crear `CustomerFactory`.
- [ ] Generar nombre.
- [ ] Generar TikTok único.
- [ ] Generar WhatsApp único.
- [ ] Crear estados:
  - `withoutTikTok`
  - `withoutWhatsapp`
  - `minimal`
- [ ] Verificar creación mediante Tinker.

Requirements:
3, 4, 13

---

## Task 3 - Customer validation and normalization

- [ ] Crear `StoreCustomerRequest`.
- [ ] Crear `UpdateCustomerRequest`.
- [ ] Implementar `prepareForValidation()`.
- [ ] Normalizar nombre.
- [ ] Normalizar TikTok:
  - trim
  - eliminar `@`
  - lowercase
  - convertir vacío a null
- [ ] Normalizar WhatsApp:
  - eliminar caracteres de formato
  - conservar únicamente dígitos
  - convertir vacío a null
- [ ] Validar TikTok único.
- [ ] Validar WhatsApp único.
- [ ] Ignorar cliente actual durante update.

Requirements:
3, 4, 5, 6, 7, 8, 13

---

## Task 4 - Customer controller

- [ ] Crear `CustomerController`.
- [ ] Implementar `index`.
- [ ] Implementar `create`.
- [ ] Implementar `store`.
- [ ] Implementar `show`.
- [ ] Implementar `edit`.
- [ ] Implementar `update`.
- [ ] No implementar destroy.

Requirements:
1, 2, 3, 4, 8, 9, 11

---

## Task 5 - Customer routes

- [ ] Registrar `customers.index`.
- [ ] Registrar `customers.create`.
- [ ] Registrar `customers.store`.
- [ ] Registrar `customers.show`.
- [ ] Registrar `customers.edit`.
- [ ] Registrar `customers.update`.
- [ ] Colocar rutas bajo middleware `auth` y `active`.
- [ ] Permitir Administrador y Vendedor.
- [ ] Añadir `whereNumber` donde corresponda.
- [ ] Verificar con `route:list`.

Requirements:
12

---

## Task 6 - Customer index and search

- [ ] Implementar búsqueda por nombre.
- [ ] Implementar búsqueda por TikTok.
- [ ] Implementar búsqueda por WhatsApp.
- [ ] Normalizar término de búsqueda TikTok.
- [ ] Normalizar término de búsqueda telefónica.
- [ ] Paginar de 25 en 25.
- [ ] Conservar query string.

Requirements:
1, 2

---

## Task 7 - Customer views

- [ ] Crear `customers/index.blade.php`.
- [ ] Crear `customers/create.blade.php`.
- [ ] Crear `customers/show.blade.php`.
- [ ] Crear `customers/edit.blade.php`.
- [ ] Mostrar valores no registrados claramente.
- [ ] No mostrar acciones de eliminación.

Requirements:
1, 3, 4, 8, 9, 11

---

## Task 8 - Navbar integration

- [ ] Añadir enlace `Clientes`.
- [ ] Mostrarlo a Administrador.
- [ ] Mostrarlo a Vendedor.
- [ ] Mantener Usuarios y Categorías exclusivos del Administrador.

Requirements:
12

---

## Task 9 - Manual customer checkpoint

Probar manualmente:

- [ ] Administrador abre `/customers`.
- [ ] Vendedor abre `/customers`.
- [ ] Crear cliente únicamente con nombre.
- [ ] Crear cliente con TikTok.
- [ ] Crear cliente con WhatsApp.
- [ ] Crear cliente con todos los campos.
- [ ] Actualizar cliente que no tenía WhatsApp.
- [ ] Consultar detalle.
- [ ] Buscar por nombre.
- [ ] Buscar por TikTok.
- [ ] Buscar por WhatsApp.
- [ ] Guest es enviado al login.

Requirements:
1-14

---

## Task 10 - Manual normalization checkpoint

Verificar:

- [ ] `@MariaLPZ` se almacena como `marialpz`.
- [ ] `MariaLPZ` entra en conflicto con `@MariaLPZ`.
- [ ] `+591 7123-4567` se almacena como `59171234567`.
- [ ] Otro formato equivalente del mismo número es rechazado.
- [ ] Dos clientes pueden tener el mismo nombre.
- [ ] Dos clientes pueden tener TikTok null.
- [ ] Dos clientes pueden tener WhatsApp null.

Requirements:
5, 6, 7, 13

---

## Task 11 - Customer Management Feature Tests

Crear:

```text
tests/Feature/Customers/CustomerManagementTest.php

Verificar:

 Admin puede listar.
 Vendedor puede listar.
 Admin puede crear.
 Vendedor puede crear.
 Nombre requerido.
 TikTok nullable.
 WhatsApp nullable.
 Cliente puede editarse.
 created_at permanece igual.
 No existe ruta de eliminación.
 Guest redirigido.

Requirements:
1, 3, 4, 8, 11, 12

Task 12 - Customer Normalization Tests

Crear:

tests/Feature/Customers/CustomerNormalizationTest.php

Verificar:

 Eliminar @ de TikTok.
 TikTok se convierte a lowercase.
 Espacios externos se eliminan.
 TikTok vacío se transforma en null.
 WhatsApp se transforma a dígitos.
 WhatsApp vacío se transforma en null.
 TikTok duplicado se rechaza.
 WhatsApp duplicado se rechaza.
 Nombres duplicados están permitidos.

Requirements:
5, 6, 7, 13

Task 13 - Customer Search Tests

Crear:

tests/Feature/Customers/CustomerSearchTest.php

Verificar:

 Buscar por nombre.
 Buscar por TikTok.
 Buscar TikTok incluyendo @.
 Buscar por WhatsApp.
 Buscar WhatsApp con formato.
 Búsqueda inexistente devuelve listado vacío.
 Resultados paginados en 25 registros.

Requirements:
1, 2

Task 14 - Full regression validation

Ejecutar:

php artisan test tests/Feature/Customers
php artisan test
php artisan route:list
php artisan migrate:status
npm run build

Todos deben finalizar correctamente.

Task 15 - Final Customer Management checkpoint
 Revisar todos los requirements.
 Revisar todas las tasks.
 Ejecutar git diff --check.
 Revisar git status.
 Confirmar que .env no está incluido.
 Ejecutar toda la suite.
 Confirmar build.
 Hacer commit de implementación.
 Push de feature/customer-management.
 Merge a main.
 Push de main.
 Confirmar working tree limpio.

## 3. Guarda primero la Spec en Git

Antes de programar, ejecuta:

powershell
git status
