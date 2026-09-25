# Design Document - Customer Management

## 1. Overview

Customer Management será el módulo interno de LiveSales encargado de registrar, localizar, consultar y actualizar compradores.

El módulo será utilizado por:

- Administrador.
- Vendedor.

Los compradores no tendrán autenticación ni acceso directo al sistema.

La implementación seguirá la arquitectura monolítica existente de LiveSales:

- Laravel 12.
- Blade.
- Bootstrap 5.
- MySQL.
- Autenticación mediante sesión.
- Middleware `auth`.
- Middleware `active`.
- Spatie Permission para roles existentes.

El módulo quedará preparado para integrarse posteriormente con:

- Live Management.
- Reservations.
- Orders.
- Confirmation workflow.
- Deliveries.
- Customer recurrence.

---

# 2. Domain Model

Se utilizará el modelo:

```text
Customer

Cada registro representa una persona que compra o intenta comprar productos mediante los Lives.

El modelo no representa un usuario autenticable.

Por lo tanto:

User != Customer

User representa trabajadores internos del sistema.

Customer representa compradores.

3. Database Design
Table: customers
customers
---------
id
name
tiktok_username nullable unique
whatsapp nullable unique
created_at
updated_at

Migration propuesta:

Schema::create('customers', function (Blueprint $table) {
    $table->id();

    $table->string('name', 150);

    $table->string('tiktok_username', 100)
        ->nullable()
        ->unique();

    $table->string('whatsapp', 30)
        ->nullable()
        ->unique();

    $table->timestamps();

    $table->index('name');
});

No se implementará:

deleted_at

durante este MVP.

Los clientes no tendrán eliminación física ordinaria.

4. Customer Model

Archivo:

app/Models/Customer.php

Responsabilidades:

Representar al cliente.
Permitir asignación de los campos autorizados.
Preparar futuras relaciones con pedidos y reservas.

Fillable:

[
    'name',
    'tiktok_username',
    'whatsapp',
]

Relaciones futuras:

Customer
   |
   ├── hasMany Reservations
   └── hasMany Orders

Estas relaciones NO se implementarán todavía porque sus tablas aún no existen.

5. Data Normalization

La normalización se realizará antes de validar y almacenar los datos.

5.1 Name

El nombre:

Se recortará con trim.
No será único.
Podrá repetirse entre distintos clientes.

Ejemplo:

"  María Pérez  "

se convertirá en:

"María Pérez"
5.2 TikTok username

El nombre de usuario TikTok:

Será opcional.
Se eliminarán espacios externos.
Se eliminará el símbolo @ inicial.
Se almacenará en minúsculas.
Si queda vacío se convertirá en null.

Ejemplos:

@MariaLPZ
MariaLPZ
  @MariaLPZ

se almacenarán como:

marialpz

Esto permitirá que sean considerados el mismo usuario.

La base de datos y la validación impedirán duplicados.

5.3 WhatsApp

WhatsApp:

Será opcional.
Permitirá al usuario introducir espacios, guiones, paréntesis y +.
Antes de validar unicidad se normalizará.
Se almacenarán únicamente los dígitos.
Si queda vacío se convertirá en null.

Ejemplo:

+591 7123-4567

se almacenará como:

59171234567

No se agregará automáticamente el prefijo 591.

Esto evita asumir un país cuando el usuario no lo proporcionó.

6. Validation

Se utilizarán dos Form Requests:

StoreCustomerRequest
UpdateCustomerRequest

Ubicación:

app/Http/Requests/

Ambos utilizarán:

prepareForValidation()

para normalizar:

name
tiktok_username
whatsapp

antes de aplicar reglas.

7. Store Validation
name
required
string
max:150
tiktok_username
nullable
string
max:100
unique:customers,tiktok_username

Formato permitido después de normalización:

letras
números
punto
guion bajo
whatsapp
nullable
string
max:30
regex de solo dígitos después de normalización
unique:customers,whatsapp
8. Update Validation

Las reglas serán equivalentes a Store.

La diferencia será la unicidad.

Al editar:

Rule::unique(...)
    ->ignore($customer)

permitirá conservar el TikTok y WhatsApp actuales del cliente.

9. CustomerController

Archivo:

app/Http/Controllers/CustomerController.php

Métodos:

index()
create()
store()
show()
edit()
update()

No existirá:

destroy()

durante esta Spec.

10. Customer Index

Ruta:

GET /customers

Permitida para:

Administrador.
Vendedor.

La consulta:

Customer::query()

permitirá búsqueda mediante:

?search=

Se buscará simultáneamente sobre:

name
tiktok_username
whatsapp

Ejemplo:

/customers?search=maria

La consulta será equivalente conceptualmente a:

WHERE name LIKE '%maria%'
   OR tiktok_username LIKE '%maria%'
   OR whatsapp LIKE '%maria%'

Los resultados se ordenarán por registros más recientes.

Paginación:

25 registros

Los parámetros de búsqueda se conservarán mediante:

withQueryString()
11. Searching Normalized Values

La búsqueda tendrá una pequeña normalización adicional.

Si el usuario busca:

@MariaLPZ

también deberá poder localizar:

marialpz

Para ello se podrá obtener:

rawSearch
normalizedTikTokSearch
normalizedPhoneSearch

No será necesario crear un motor de búsqueda independiente.

12. Create Customer

Ruta:

GET /customers/create
POST /customers

Permitida para:

Administrador.
Vendedor.

El formulario incluirá:

Nombre *
Usuario TikTok
WhatsApp

Solo el nombre será obligatorio.

Esto permite el registro rápido durante ventas.

13. Quick Registration Compatibility

Esta Spec no creará todavía un modal específico para Modo Live.

Sin embargo, la arquitectura permitirá reutilizar:

StoreCustomerRequest
Customer model
normalización
reglas de unicidad

cuando se implemente Live Management.

El futuro flujo podrá registrar un cliente con:

name
tiktok_username

sin requerir WhatsApp.

14. Show Customer

Ruta:

GET /customers/{customer}

Permitida para:

Administrador.
Vendedor.

Mostrará:

Nombre
TikTok
WhatsApp
Fecha de registro
Última modificación

Cuando no exista TikTok:

No registrado

Cuando no exista WhatsApp:

No registrado

También se reservará visualmente un área futura para:

Pedidos
Compras completadas
Cliente nuevo/recurrente

pero no mostrará datos inventados.

15. Edit Customer

Rutas:

GET /customers/{customer}/edit
PUT /customers/{customer}

Permitidas para:

Administrador.
Vendedor.

Será posible completar posteriormente información faltante.

Ejemplo:

Registro durante Live
Nombre: María
TikTok: marialpz
WhatsApp: null

Después:

Edición
WhatsApp: 59171234567

El mismo registro será actualizado.

No se creará un nuevo cliente.

16. Recurrent Customer

La condición de cliente recurrente NO será almacenada mediante:

is_recurrent

ni ningún booleano editable.

La regla futura será:

Customer is recurrent
IF
exists Order
WHERE customer_id = customer.id
AND status = ENTREGADO

Como Orders todavía no existe:

No se implementará la relación.
No se añadirá columna.
No se fingirá el estado recurrente.

Esta regla será implementada cuando exista Order Management.

17. Routes

Todas estarán dentro del grupo existente:

Route::middleware(['auth', 'active'])->group(...)

Las rutas serán:

GET    /customers
GET    /customers/create
POST   /customers
GET    /customers/{customer}
GET    /customers/{customer}/edit
PUT    /customers/{customer}

Nombres:

customers.index
customers.create
customers.store
customers.show
customers.edit
customers.update

No llevarán:

role:Administrador

porque tanto Administrador como Vendedor gestionan clientes.

Las rutas estáticas deberán declararse antes de:

/customers/{customer}

Además se utilizará:

->whereNumber('customer')

en las rutas con identificador.

18. Views

Se crearán:

resources/views/customers/
├── index.blade.php
├── create.blade.php
├── show.blade.php
└── edit.blade.php

Todas extenderán:

@extends('layouts.app')
19. Navbar

Se agregará:

Clientes

al navbar principal.

Será visible tanto para:

Administrador
Vendedor

Por lo tanto quedará fuera de:

@role('Administrador')

Conceptualmente:

Dashboard
Productos
Clientes

Administrador además:
Usuarios
Categorías
20. Customer Index UI

La pantalla incluirá:

Título Clientes.
Botón Nuevo cliente.
Campo de búsqueda.
Botón Buscar.
Botón Limpiar.
Tabla.
Paginación.

Columnas:

Nombre
TikTok
WhatsApp
Registrado
Acciones

Acciones:

Ver
Editar

No habrá botón eliminar.

21. Customer Detail UI

La vista de detalle mostrará:

Información del cliente

y botones:

Editar
Volver

Se podrá incluir una sección:

Historial comercial

con un mensaje temporal:

El historial de pedidos estará disponible cuando se implemente Order Management.
22. Security

Las rutas requerirán:

auth
active

Por tanto:

Guest
/customers

→ redirect login.

Usuario inactivo

Será gestionado por:

EnsureUserIsActive
Administrador

Acceso permitido.

Vendedor

Acceso permitido.

23. Database Integrity

Además de Form Requests, la base de datos protegerá:

tiktok_username UNIQUE
whatsapp UNIQUE

Ambos serán nullable.

MySQL permitirá múltiples valores NULL en índices únicos.

Esto permite clientes aún sin TikTok o WhatsApp.

24. Factory

Se creará:

database/factories/CustomerFactory.php

Permitirá generar clientes para tests.

Estados opcionales:

withoutTikTok()
withoutWhatsapp()
minimal()
25. Tests

Se utilizarán Feature Tests con:

RefreshDatabase

Tests principales:

CustomerManagementTest

Verificará:

Administrador puede ver clientes.
Vendedor puede ver clientes.
Administrador puede crear.
Vendedor puede crear.
Nombre obligatorio.
TikTok opcional.
WhatsApp opcional.
Editar cliente.
created_at no cambia.
Guest redirigido.
CustomerNormalizationTest

Verificará:

@MariaLPZ
→ marialpz

y:

+591 7123-4567
→ 59171234567

También:

TikTok duplicado rechazado.
WhatsApp duplicado rechazado.
Dos clientes pueden compartir nombre.
CustomerSearchTest

Verificará:

Buscar por nombre.
Buscar por TikTok.
Buscar TikTok usando @.
Buscar por WhatsApp.
Sin coincidencias.
Paginación de 25.
26. Transaction Requirements

El CRUD de Customer implica una sola tabla.

No se requieren transacciones explícitas para:

create
update

porque cada operación afecta únicamente un registro simple.

Cuando Customers se integre posteriormente con Reservations y Orders, las operaciones que afecten varias entidades sí deberán utilizar transacciones.

27. No Destructive Delete

No se implementará:

CustomerController::destroy()

No habrá:

DELETE /customers/{customer}

No habrá botón:

Eliminar

La conservación del registro permitirá mantener consistencia futura con ventas y reservas.

28. Future Integration

El identificador:

customers.id

será utilizado posteriormente mediante:

customer_id

en entidades como:

reservations
orders

Customer Management permanecerá independiente de dichos módulos hasta que se implementen sus Specs.

29. Out of Scope

Customer Management NO implementará:

Reservas.
Pedidos.
Pagos.
Entregas.
Lives.
Mensajes de WhatsApp.
Integración TikTok.
Historial real de compras.
Cliente recurrente calculado.
Eliminación de clientes.
Login de clientes.
