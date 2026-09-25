# Design Document - Live Management

## 1. Overview

Live Management será el módulo encargado de representar las transmisiones de venta realizadas por el negocio.

Permitirá:

- Crear Lives.
- Programarlos.
- Iniciarlos.
- Finalizarlos.
- Cancelarlos.
- Asociar productos.
- Definir un precio especial por Live.
- Consultar Lives anteriores.

El módulo quedará preparado para el futuro Modo Live y Reservation Management.

La implementación seguirá la arquitectura actual de LiveSales:

- Laravel 12.
- PHP 8.3+.
- MySQL.
- Blade.
- Bootstrap 5.
- Sesiones Laravel.
- Middleware auth + active.
- Spatie Permission.
- Feature Tests con PHPUnit.

---

# 2. Main Domain Entities

Se crearán:

```text
LiveSession
LiveProduct

LiveSession
Representa una transmisión.
LiveProduct
Representa un producto incluido dentro de una transmisión y conserva:
live_session_id
product_id
live_price

Por tanto:
LiveSession
   |
   | hasMany
   v
LiveProduct
   |
   | belongsTo
   v
Product

Esta entidad explícita es preferible a utilizar únicamente una tabla pivot anónima porque la relación posee información propia y podrá integrarse posteriormente con Reservation.
3. Live Status
Se creará:
app/Enums/LiveStatus.php

Valores:
PROGRAMADO
ACTIVO
FINALIZADO
CANCELADO

Enum:
enum LiveStatus: string
{
    case SCHEDULED = 'PROGRAMADO';
    case ACTIVE = 'ACTIVO';
    case FINISHED = 'FINALIZADO';
    case CANCELLED = 'CANCELADO';

    public static function values(): array
    {
        return array_column(
            self::cases(),
            'value'
        );
    }
}

4. Database - live_sessions
Tabla:
live_sessions
-------------
id
name
scheduled_at nullable
started_at nullable
ended_at nullable
notes nullable
status
created_at
updated_at

Migración conceptual:
Schema::create('live_sessions', function (Blueprint $table) {
    $table->id();

    $table->string('name', 150);

    $table->dateTime('scheduled_at')
        ->nullable();

    $table->dateTime('started_at')
        ->nullable();

    $table->dateTime('ended_at')
        ->nullable();

    $table->text('notes')
        ->nullable();

    $table->string('status', 20)
        ->default('PROGRAMADO');

    $table->timestamps();

    $table->index('status');
    $table->index('scheduled_at');
});

No se utilizará:
deleted_at

durante el MVP.
5. Database - live_products
Tabla:
live_products
-------------
id
live_session_id
product_id
live_price
created_at
updated_at

Migración conceptual:
Schema::create('live_products', function (Blueprint $table) {
    $table->id();

    $table->foreignId('live_session_id')
        ->constrained('live_sessions')
        ->cascadeOnDelete();

    $table->foreignId('product_id')
        ->constrained()
        ->restrictOnDelete();

    $table->decimal(
        'live_price',
        10,
        2
    );

    $table->timestamps();

    $table->unique([
        'live_session_id',
        'product_id',
    ]);
});

Aunque cascadeOnDelete() exista técnicamente para mantener integridad referencial, Live Management no proporcionará ninguna operación ordinaria para eliminar físicamente un Live.
El índice UNIQUE impedirá agregar el mismo Product dos veces al mismo Live.
6. LiveSession Model
Archivo:
app/Models/LiveSession.php

Fillable:
[
    'name',
    'scheduled_at',
    'started_at',
    'ended_at',
    'notes',
    'status',
]

Casts:
protected function casts(): array
{
    return [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'status' => LiveStatus::class,
    ];
}

Relaciones:
public function liveProducts()
{
    return $this->hasMany(
        LiveProduct::class
    );
}

Podrá existir además una relación de conveniencia:
public function products()

mediante belongsToMany si resulta útil para consultas.
La relación principal para operaciones será liveProducts.
7. LiveProduct Model
Archivo:
app/Models/LiveProduct.php

Fillable:
[
    'live_session_id',
    'product_id',
    'live_price',
]

Cast:
'live_price' => 'decimal:2'

Relaciones:
LiveProduct belongsTo LiveSession
LiveProduct belongsTo Product

8. Product Integration
Se agregará a:
app/Models/Product.php

una relación:
public function liveProducts()
{
    return $this->hasMany(
        LiveProduct::class
    );
}

No se cambiará ninguna regla existente del catálogo.
Agregar un Product a un Live:
NO cambia:
Product.status
Product.base_price

Ejemplo:
Product
base_price = 30 Bs

LiveProduct
live_price = 25 Bs

El Product continúa:
DISPONIBLE

hasta que un flujo futuro de Reservation lo reserve.
9. Create Live Validation
Se creará:
StoreLiveSessionRequest

Campos:
name
required
string
max:150

scheduled_at
nullable
date

No se exigirá que sea futura porque puede ser necesario registrar un Live de manera inmediata.
notes
nullable
string
max:5000

El usuario NO enviará el status inicial.
El sistema establecerá:
PROGRAMADO

10. Update Live Validation
Se creará:
UpdateLiveSessionRequest

Validará los mismos campos editables:
name
scheduled_at
notes

No permitirá cambiar:
status
started_at
ended_at

mediante el formulario normal.
Además, solamente un Live:
PROGRAMADO

podrá editar su información principal.
11. Live Product Validation
Se creará:
StoreLiveProductRequest
UpdateLiveProductRequest

product_id
required
integer
exists:products,id

live_price
required
numeric
min:0
decimal:0,2

La disponibilidad del producto y el estado del Live se validarán adicionalmente en las Actions.
12. LiveSessionController
Archivo:
app/Http/Controllers/LiveSessionController.php

Métodos:
index()
create()
store()
show()
edit()
update()

No existirá:
destroy()

Los cambios de estado utilizarán Actions y endpoints explícitos.
13. LiveProductController
Archivo:
app/Http/Controllers/LiveProductController.php

Métodos:
store()
update()
destroy()

En este caso destroy() significa:
retirar una asociación LiveProduct

NO significa eliminar el Product del catálogo.
Solo estará permitido mientras el Live esté:
PROGRAMADO
ACTIVO

14. Create Live
Al crear un Live:
name = request
scheduled_at = request
notes = request
status = PROGRAMADO
started_at = null
ended_at = null

El cliente HTTP no podrá decidir el status inicial.
15. StartLiveAction
Archivo:
app/Actions/Lives/StartLiveAction.php

Responsabilidad:
PROGRAMADO -> ACTIVO

Validaciones:
1. El Live debe estar PROGRAMADO.
2. No debe existir otro Live ACTIVO.
3. started_at debe estar vacío antes de iniciar.
Operación:
status = ACTIVO
started_at = now()

Se utilizará:
DB::transaction(...)

y bloqueo de registros apropiado para reducir conflictos entre operaciones concurrentes.
La aplicación verificará nuevamente dentro de la transacción que no exista otro Live ACTIVO.
El MVP está diseñado para una operación local de baja concurrencia, pero la lógica permanecerá protegida en servidor.
16. One Active Live Rule
La regla será:
COUNT(
    live_sessions
    WHERE status = ACTIVO
) <= 1

No se implementará únicamente mediante JavaScript.
StartLiveAction será el punto autorizado para iniciar Lives.
Esto permitirá posteriormente obtener el contexto del Modo Live mediante:
LiveSession::where(
    'status',
    LiveStatus::ACTIVE
)->first();

17. FinishLiveAction
Archivo:
app/Actions/Lives/FinishLiveAction.php

Transición permitida:
ACTIVO -> FINALIZADO

Operación:
status = FINALIZADO
ended_at = now()

No modificará:
Product.status
LiveProduct
futuras reservas
futuros pedidos

18. CancelLiveAction
Archivo:
app/Actions/Lives/CancelLiveAction.php

Transiciones permitidas:
PROGRAMADO -> CANCELADO
ACTIVO -> CANCELADO

Si el Live estaba ACTIVO:
ended_at = now()

Si estaba PROGRAMADO:
ended_at = null

No podrá cancelarse:
FINALIZADO
CANCELADO

19. AddLiveProductAction
Archivo:
app/Actions/Lives/AddLiveProductAction.php

Validará:
1. Live estado PROGRAMADO o ACTIVO.
2. Product estado DISPONIBLE.
3. Product no está ya relacionado con ese Live.
4. live_price válido según Request.
Creará:
LiveProduct

No modificará:
Product.status
Product.base_price

20. Initial Live Price
En la interfaz, cuando se seleccione un Product, podrá sugerirse:
Product.base_price

como precio inicial.
Sin embargo, live_price será enviado y validado explícitamente.
No se dependerá únicamente de JavaScript para determinarlo.
21. UpdateLiveProductPriceAction
Archivo:
app/Actions/Lives/UpdateLiveProductPriceAction.php

Permitido si Live:
PROGRAMADO
ACTIVO

No permitido si:
FINALIZADO
CANCELADO

Modificará únicamente:
live_products.live_price

No:
products.base_price

22. RemoveLiveProductAction
Archivo:
app/Actions/Lives/RemoveLiveProductAction.php

Permitido si:
PROGRAMADO
ACTIVO

Eliminará el registro:
LiveProduct

No eliminará:
Product

Cuando Reservation exista se agregará una nueva restricción que impedirá retirar asociaciones utilizadas por reservas válidas.
Esta regla todavía no será simulada.
23. Editing Live Information
Solo:
PROGRAMADO

permitirá editar:
name
scheduled_at
notes

Si el estado es:
ACTIVO
FINALIZADO
CANCELADO

el Controller rechazará la edición.
Los precios de productos sí seguirán siendo editables durante ACTIVO mediante el endpoint específico de LiveProduct.
24. Live Listing
Ruta:
GET /lives

Permitida para:
Administrador
Vendedor

Parámetro opcional:
?status=

Valores:
PROGRAMADO
ACTIVO
FINALIZADO
CANCELADO

Consulta:
latest()
paginate(25)
withQueryString()

Se cargará:
liveProducts count

mediante:
withCount('liveProducts')

25. Show Live
Ruta:
GET /lives/{liveSession}

Mostrará:
Nombre
Estado
Fecha programada
Inicio real
Finalización
Notas
Cantidad de productos
Productos

Cada producto mostrará:
Código
Nombre
Estado actual
Precio base
Precio Live

La página también contendrá las acciones disponibles según estado.
26. Product Selection
En un Live PROGRAMADO o ACTIVO se mostrarán para agregar únicamente productos:
DISPONIBLE

y que todavía no pertenezcan al Live.
La consulta podrá realizar:
Product::where(
    'status',
    ProductStatus::AVAILABLE
)

excluyendo IDs existentes en live_products.
La validación del Action volverá a comprobar estas reglas aunque el producto no aparezca en el select.
27. Routes
Todas estarán dentro de:
auth
active

Administrador y Vendedor podrán utilizarlas.
LiveSession routes
GET    /lives
GET    /lives/create
POST   /lives
GET    /lives/{liveSession}
GET    /lives/{liveSession}/edit
PUT    /lives/{liveSession}

PATCH  /lives/{liveSession}/start
PATCH  /lives/{liveSession}/finish
PATCH  /lives/{liveSession}/cancel

Nombres:
lives.index
lives.create
lives.store
lives.show
lives.edit
lives.update
lives.start
lives.finish
lives.cancel

LiveProduct routes
POST   /lives/{liveSession}/products
PUT    /lives/{liveSession}/products/{liveProduct}
DELETE /lives/{liveSession}/products/{liveProduct}

Nombres:
lives.products.store
lives.products.update
lives.products.destroy

No existirá:
lives.destroy

28. Route Model Binding Safety
Las Actions verificarán que:
LiveProduct.live_session_id
==
LiveSession.id

antes de:
update
destroy

Esto evita que un usuario intente utilizar una URL como:
/lives/1/products/50

cuando LiveProduct 50 pertenece realmente a otro Live.
29. Views
Se crearán:
resources/views/lives/
├── index.blade.php
├── create.blade.php
├── show.blade.php
└── edit.blade.php

La gestión de LiveProduct podrá realizarse directamente en:
lives/show.blade.php

No será necesaria una pantalla independiente para cada LiveProduct.
30. Live UI States
Los estados podrán visualizarse mediante badges.
Ejemplo conceptual:
PROGRAMADO
ACTIVO
FINALIZADO
CANCELADO

La interfaz mostrará acciones contextuales.
PROGRAMADO
Editar
Agregar producto
Modificar precio
Retirar producto
Iniciar
Cancelar

ACTIVO
Agregar producto
Modificar precio
Retirar producto
Finalizar
Cancelar

FINALIZADO
Solo consultar

CANCELADO
Solo consultar

31. Navbar
Se agregará:
Lives

para:
Administrador
Vendedor

Conceptualmente:
Dashboard
Productos
Clientes
Lives

Administrador conservará además:
Usuarios
Categorías

32. Modo Live Preparation
Todavía NO se implementará la pantalla final de operación rápida.
Sin embargo, el módulo permitirá obtener:
Live ACTIVO

junto con:
LiveProducts
Products
live_price

Esto será la base de:
Modo Live

La futura funcionalidad podrá mostrar únicamente productos de la transmisión activa.
33. Reservation Future Integration
Reservation podrá relacionarse posteriormente con:
live_session_id
customer_id
product_id

y potencialmente utilizar live_product_id como referencia técnica si el diseño de Reservation lo requiere.
El precio acordado:
agreed_price

NO será agregado todavía a LiveProduct.
La separación será:
Product.base_price
    = precio general

LiveProduct.live_price
    = precio anunciado en el Live

Reservation.agreed_price
    = precio finalmente acordado

34. No Product Status Mutation
Live Management no establecerá:
RESERVADO
VENDIDO

en Product.
Ejemplo:
Agregar vestido al Live
-> Product sigue DISPONIBLE

Iniciar Live
-> Product sigue DISPONIBLE

Finalizar Live
-> Product sigue DISPONIBLE

Los cambios a RESERVADO comenzarán en Reservation Management.
35. Historical Conservation
Un Live FINALIZADO conservará:
LiveSession
LiveProducts
live_price

Un Live CANCELADO conservará igualmente las asociaciones que tuviera en ese momento.
No se permitirá modificar ni retirar productos una vez alcanzados esos estados.
36. Factories
Se crearán:
LiveSessionFactory
LiveProductFactory

LiveSessionFactory tendrá por defecto:
status = PROGRAMADO
started_at = null
ended_at = null

Estados útiles:
active()
finished()
cancelled()

LiveProductFactory utilizará:
LiveSession::factory()
Product::factory()
live_price

37. Testing Strategy
Se utilizará:
RefreshDatabase

Los tests no dependerán de datos manuales.
Se probarán tanto:
Administrador
Vendedor
Guest

cuando corresponda.
38. Live Management Tests
Archivo:
tests/Feature/Lives/LiveManagementTest.php

Verificará:
- Admin puede listar.
- Vendedor puede listar.
- Crear Live.
- Estado inicial PROGRAMADO.
- scheduled_at nullable.
- Editar PROGRAMADO.
- No editar ACTIVO.
- No existe destroy.
- Guest redirigido.
- Paginación.
- Filtro por status.
39. Status Transition Tests
Archivo:
tests/Feature/Lives/LiveStatusTransitionTest.php

Verificará:
- PROGRAMADO -> ACTIVO.
- started_at registrado.
- Solo un ACTIVO.
- ACTIVO -> FINALIZADO.
- ended_at registrado.
- PROGRAMADO -> CANCELADO.
- ACTIVO -> CANCELADO.
- FINALIZADO no puede iniciar.
- CANCELADO no puede iniciar.
- FINALIZADO no puede cancelar.
- CANCELADO no puede cancelar nuevamente.
40. Live Product Tests
Archivo:
tests/Feature/Lives/LiveProductManagementTest.php

Verificará:
- Agregar DISPONIBLE a PROGRAMADO.
- Agregar DISPONIBLE a ACTIVO.
- Producto mantiene status DISPONIBLE.
- base_price no cambia.
- live_price se almacena.
- Producto duplicado rechazado.
- RESERVADO rechazado.
- VENDIDO rechazado.
- INACTIVO rechazado.
- Producto no puede agregarse a FINALIZADO.
- Producto no puede agregarse a CANCELADO.
- Actualizar live_price.
- Retirar producto.
- No retirar en FINALIZADO.
- No retirar en CANCELADO.
- Validar pertenencia LiveProduct -> LiveSession.
41. Full Regression
Antes de cerrar la Feature se ejecutará:
php artisan test tests/Feature/Lives
php artisan test
php artisan route:list
php artisan migrate:status
npm run build
git diff --check

Authentication, Catalog y Customer Management deberán continuar en verde.
42. Out of Scope
Esta Spec no implementará:
- Reservation.
- "Mío".
- Customer selection dentro del Live.
- Orders.
- agreed_price.
- Payments.
- Delivery.
- Confirmation deadline.
- Recordatorios.
- TikTok API.
- WhatsApp API.
- Automatización de mensajes.
