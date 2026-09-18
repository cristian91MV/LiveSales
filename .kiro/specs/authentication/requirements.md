# Requirements Document

## Introduction

Este documento define los requisitos del módulo de autenticación, gestión de usuarios y roles de LiveSales para el MVP.

LiveSales es un sistema de gestión interna para negocios que realizan ventas mediante transmisiones en vivo. Solo existirán usuarios internos (Administrador y Vendedor). No existirán cuentas públicas para clientes ni compradores en el MVP.

El sistema utilizará Laravel 12 con Spatie Laravel Permission para el manejo de roles y permisos. La autenticación se realizará mediante las capacidades nativas del ecosistema oficial de Laravel.

---

## Glossary

- **Sistema**: La aplicación web LiveSales.
- **Usuario**: Persona registrada en LiveSales con credenciales de acceso (correo y contraseña). Exclusivamente personal interno del negocio.
- **Administrador**: Rol con acceso completo al sistema, incluyendo gestión de usuarios, roles y todas las funcionalidades del MVP.
- **Vendedor**: Rol con acceso a las operaciones de venta (productos, clientes, Lives, reservas, pedidos, pagos y entregas), sin acceso a administración de usuarios.
- **Credenciales**: Combinación de correo electrónico y contraseña utilizadas para autenticar a un usuario.
- **Sesión**: Estado de autenticación activo de un usuario dentro del sistema.
- **Usuario activo**: Usuario cuyo estado es activo y puede iniciar sesión y utilizar el sistema.
- **Usuario inactivo**: Usuario desactivado por el Administrador, que no puede iniciar sesión ni realizar ninguna operación en el sistema.
- **Spatie Laravel Permission**: Paquete de terceros utilizado para gestionar roles y permisos en Laravel.
- **Guard**: Mecanismo de Laravel para identificar cómo se autentican los usuarios en cada contexto.

---

## Requirements

### Requisito 1: Inicio de sesión

**Historia de usuario:** Como usuario interno, quiero iniciar sesión con mis credenciales, para acceder a las funcionalidades del sistema que corresponden a mi rol.

#### Criterios de aceptación

1. WHEN un usuario envía un correo electrónico y contraseña válidos correspondientes a un usuario activo, THE Sistema SHALL autenticar al usuario, crear una sesión activa y redirigirlo al dashboard principal.
2. IF el correo electrónico no corresponde a ningún usuario registrado, THEN THE Sistema SHALL rechazar el intento de acceso y mostrar un mensaje de error genérico sin indicar cuál campo es incorrecto.
3. IF el correo electrónico corresponde a un usuario registrado pero la contraseña es incorrecta, THEN THE Sistema SHALL rechazar el intento de acceso y mostrar el mismo mensaje de error genérico utilizado para credenciales incorrectas.
4. IF un usuario cuyo estado es inactivo intenta iniciar sesión con credenciales correctas, THEN THE Sistema SHALL rechazar el intento de acceso y mostrar un mensaje indicando que la cuenta está desactivada.
5. WHEN un usuario intenta acceder a cualquier ruta protegida sin una sesión activa, THE Sistema SHALL redirigir al usuario al formulario de inicio de sesión.
6. THE Sistema SHALL proteger el formulario de inicio de sesión con un token CSRF y rechazar cualquier solicitud de autenticación que no incluya un token válido.
7. THE Sistema SHALL almacenar las contraseñas utilizando el mecanismo de hashing seguro configurado por Laravel y nunca almacenarlas en texto plano.
8. IF un usuario envía el formulario de inicio de sesión con el campo de correo electrónico vacío o con formato inválido, THEN THE Sistema SHALL rechazar la solicitud y mostrar un mensaje de error de validación indicando que el correo es requerido y debe tener formato válido.
9. IF un usuario envía el formulario de inicio de sesión con el campo de contraseña vacío, THEN THE Sistema SHALL rechazar la solicitud y mostrar un mensaje de error de validación indicando que la contraseña es requerida.
10. IF un mismo usuario realiza 5 intentos de inicio de sesión fallidos consecutivos desde el mismo IP, THEN THE Sistema SHALL bloquear temporalmente nuevos intentos de inicio de sesión para esa combinación de IP y correo durante 60 segundos y mostrar un mensaje indicando que debe esperar antes de intentarlo nuevamente.

---

### Requisito 2: Cierre de sesión

**Historia de usuario:** Como usuario autenticado, quiero cerrar mi sesión, para que ninguna otra persona pueda acceder al sistema desde el mismo dispositivo.

#### Criterios de aceptación

1. WHEN un usuario autenticado solicita cerrar sesión, THE Sistema SHALL invalidar la sesión activa, regenerar el token de sesión y redirigir al usuario al formulario de inicio de sesión.
2. THE Sistema SHALL requerir un token CSRF válido en la solicitud de cierre de sesión para prevenir cierres de sesión no autorizados.
3. WHEN la sesión de un usuario ha sido cerrada, THE Sistema SHALL rechazar cualquier intento de acceder a rutas protegidas y redirigir al usuario al formulario de inicio de sesión.
4. IF la solicitud de cierre de sesión contiene un token CSRF inválido o ausente, THEN THE Sistema SHALL rechazar la solicitud con un error que indique token inválido y mantener la sesión activa sin modificaciones.

---

### Requisito 3: Protección de rutas internas

**Historia de usuario:** Como negocio, quiero que todas las áreas internas del sistema estén protegidas, para que solo usuarios autenticados puedan utilizarlas.

#### Criterios de aceptación

1. THE Sistema SHALL requerir autenticación activa para acceder a cualquier ruta interna del sistema, con excepción del formulario de inicio de sesión.
2. WHEN un usuario no autenticado intenta acceder a una ruta interna, THE Sistema SHALL redirigir al formulario de inicio de sesión sin exponer información sobre la ruta o el contenido solicitado.
3. THE Sistema SHALL validar el estado activo del usuario en cada solicitud autenticada entrante.
4. IF el usuario asociado a una sesión activa tiene estado inactivo en el momento de una solicitud, THEN THE Sistema SHALL invalidar la sesión, rechazar la solicitud y redirigir al usuario al formulario de inicio de sesión.
5. THE Sistema SHALL verificar los permisos de cada operación en el servidor antes de ejecutarla, independientemente de si los controles de interfaz están ocultos o visibles.
6. IF un usuario autenticado intenta acceder a una ruta para la cual no tiene permiso, THEN THE Sistema SHALL rechazar la solicitud y devolver una respuesta de acceso denegado sin modificar ningún dato.

---

### Requisito 4: Gestión de usuarios por el Administrador

**Historia de usuario:** Como Administrador, quiero poder crear, consultar y editar usuarios internos, para gestionar quién tiene acceso al sistema.

#### Criterios de aceptación

1. THE Sistema SHALL permitir al Administrador consultar la lista de usuarios registrados, mostrando nombre, correo electrónico, rol asignado y estado (activo/inactivo), paginada en bloques de hasta 25 usuarios por página.
2. WHEN el Administrador crea un nuevo usuario, THE Sistema SHALL requerir nombre (máximo 255 caracteres), correo electrónico único (máximo 255 caracteres, formato válido de correo electrónico), contraseña y asignación de rol (Administrador o Vendedor).
3. IF el correo electrónico proporcionado al crear un usuario ya existe en el sistema, THEN THE Sistema SHALL rechazar la operación y mostrar un mensaje de error indicando que el correo ya está en uso.
4. IF algún campo obligatorio al crear un usuario está ausente o no cumple el formato requerido, THEN THE Sistema SHALL rechazar la operación y mostrar un mensaje de error indicando qué campo falló la validación.
5. WHEN el Administrador edita un usuario existente, THE Sistema SHALL permitir modificar el nombre, correo electrónico y rol asignado.
6. IF el nuevo correo electrónico proporcionado al editar un usuario ya pertenece a otro usuario del sistema, THEN THE Sistema SHALL rechazar la operación y mostrar un mensaje de error indicando que el correo ya está en uso.
7. WHEN el Administrador edita un usuario y proporciona una nueva contraseña, THE Sistema SHALL requerir que la nueva contraseña tenga al menos 8 caracteres y no supere 255 caracteres.
8. WHEN el Administrador no proporciona una nueva contraseña al editar un usuario, THE Sistema SHALL conservar la contraseña existente sin modificarla.
9. THE Sistema SHALL almacenar toda contraseña de usuario utilizando un algoritmo de hash seguro; la contraseña en texto plano no deberá persistirse ni registrarse.
10. THE Sistema SHALL registrar los campos created_at y updated_at con la fecha y hora de creación y de la última modificación de cada usuario.
11. WHEN el Administrador crea un nuevo usuario correctamente, THE Sistema SHALL establecerlo como activo por defecto.
---

### Requisito 5: Activación y desactivación de usuarios

**Historia de usuario:** Como Administrador, quiero poder activar y desactivar usuarios, para controlar quién puede acceder al sistema sin necesidad de eliminar cuentas.

#### Criterios de aceptación

1. WHEN el Administrador desactiva un usuario con estado activo, THE Sistema SHALL cambiar el estado del usuario a inactivo e impedir que ese usuario pueda iniciar sesión.
2. WHEN el Administrador activa un usuario con estado inactivo, THE Sistema SHALL cambiar el estado del usuario a activo y permitir que ese usuario pueda iniciar sesión con sus credenciales existentes.
3. IF un usuario con estado inactivo intenta iniciar sesión, THEN THE Sistema SHALL rechazar el intento y mostrar un mensaje de error indicando que la cuenta está desactivada, sin revelar información adicional sobre otros usuarios.
4. THE Sistema SHALL garantizar que exista al menos un usuario activo con rol Administrador en todo momento.
5. IF una operación de desactivación provocaría que el sistema quede sin ningún Administrador activo, THEN THE Sistema SHALL rechazar la operación, conservar el estado actual y mostrar un mensaje descriptivo.
6. IF el Administrador intenta activar o desactivar un usuario que no existe en el sistema, THEN THE Sistema SHALL rechazar la operación y mostrar un mensaje de error indicando que el usuario no fue encontrado.

---

### Requisito 6: Asignación de roles

**Historia de usuario:** Como Administrador, quiero asignar el rol Administrador o Vendedor a cada usuario, para determinar qué operaciones puede realizar cada persona en el sistema.

#### Criterios de aceptación

1. THE Sistema SHALL asignar a cada usuario exactamente uno de los dos roles disponibles: Administrador o Vendedor.
2. WHEN se crea un nuevo usuario, THE Sistema SHALL requerir que se especifique el rol antes de guardar el registro.
3. WHEN el Administrador cambia el rol de un usuario, THE Sistema SHALL aplicar los nuevos permisos a partir de la siguiente solicitud autenticada de ese usuario, sin requerir que el usuario cierre sesión manualmente.
4. THE Sistema SHALL verificar el rol del usuario autenticado en el servidor antes de permitir cualquier operación protegida, sin depender exclusivamente de controles de interfaz.
5. THE Sistema SHALL impedir que un Vendedor realice operaciones de administración de usuarios, incluso si accede directamente a las rutas mediante URL.
6. IF un usuario autenticado intenta ejecutar una operación para la cual su rol no tiene permiso, THEN THE Sistema SHALL rechazar la solicitud y responder con un mensaje indicando acceso no autorizado, sin modificar ningún dato.
7. IF se intenta crear un usuario sin especificar un rol válido, THEN THE Sistema SHALL rechazar el registro y responder con un mensaje indicando que el rol es obligatorio.
8. IF cambiar el rol de un usuario provocaría que el sistema quede sin ningún Administrador activo, THEN THE Sistema SHALL rechazar la operación y conservar el rol actual.
---

### Requisito 7: Restricciones del Vendedor

**Historia de usuario:** Como sistema, quiero que el Vendedor no pueda acceder a la gestión de usuarios, para mantener la separación de responsabilidades definida en los roles.

#### Criterios de aceptación

1. WHEN un Vendedor intenta acceder a cualquier ruta de gestión de usuarios mediante el navegador, THE Sistema SHALL denegar el acceso y redirigir a una página de error de autorización denegada.
2. WHEN un Vendedor realiza una solicitud HTTP directa a cualquier endpoint de gestión de usuarios (crear, editar, activar, desactivar, cambiar rol), THE Sistema SHALL denegar la solicitud con un código de respuesta HTTP 403 sin ejecutar ninguna modificación.
3. THE Sistema SHALL impedir que un Vendedor cree, edite, active, desactive o modifique el rol de cualquier usuario.
4. THE Sistema SHALL verificar la autorización en el servidor para cada acción protegida antes de ejecutarla, sin considerar suficiente la ausencia de controles en la interfaz.

---

### Requisito 8: Integridad y consistencia de datos de usuario

**Historia de usuario:** Como sistema, quiero que los datos de los usuarios mantengan integridad y consistencia, para evitar estados inválidos en el sistema.

#### Criterios de aceptación

1. THE Sistema SHALL garantizar que el correo electrónico de cada usuario sea único dentro del sistema, aplicando esta restricción tanto en la capa de aplicación como en la base de datos mediante un índice único.
2. THE Sistema SHALL conservar el campo created_at con la fecha y hora de creación de cada usuario y no permitir su modificación posterior.
3. THE Sistema SHALL conservar el campo updated_at con la fecha y hora de la última modificación de cada usuario.
4. THE Sistema SHALL utilizar estado inactivo (is_active = false) en lugar de eliminación física para usuarios que ya no deben tener acceso, preservando la trazabilidad histórica de los registros.
5. IF se intenta crear un usuario con correo electrónico duplicado, THEN THE Sistema SHALL rechazar la operación y devolver un mensaje de error indicando que el correo ya está registrado.
6. IF se intenta crear un usuario con campos obligatorios ausentes o con formato inválido, THEN THE Sistema SHALL rechazar la operación y devolver mensajes de error descriptivos indicando qué campo falló la validación.

---

### Requisito 9: Seed inicial del sistema

**Historia de usuario:** Como negocio, quiero que el sistema tenga configurados los roles y un usuario Administrador inicial desde el primer despliegue, para poder comenzar a gestionar el sistema sin configuración manual adicional.

#### Criterios de aceptación

1. THE Sistema SHALL crear automáticamente los roles Administrador y Vendedor durante el proceso de seed inicial.
2. THE Sistema SHALL crear un usuario Administrador inicial con nombre, correo electrónico y contraseña configurables mediante variables de entorno, para no exponer credenciales en el código fuente.
3. THE Sistema SHALL almacenar las contraseñas utilizando el mecanismo de hashing seguro configurado por Laravel y nunca almacenarlas en texto plano.
4. WHEN el seed inicial se ejecuta más de una vez, THE Sistema SHALL evitar crear registros duplicados de roles o del usuario Administrador inicial utilizando operaciones idempotentes (firstOrCreate o equivalente).
5. IF las variables de entorno necesarias para crear el usuario Administrador inicial no están definidas, THEN THE Sistema SHALL registrar un error descriptivo y no crear un usuario con credenciales vacías o predeterminadas inseguras.
