# Design Document

## 1. Overview

Catalog Management será el módulo responsable de administrar las categorías y productos internos de LiveSales.

El módulo servirá como base para funcionalidades posteriores como:

- Sesiones Live.
- Modo Live.
- Reservas.
- Pedidos.
- Estadísticas.

Durante el MVP cada producto representa exactamente una unidad física.

Ejemplo:

R025 → Vestido rojo → una unidad física.

Si existen tres prendas físicamente iguales deberán existir tres productos diferentes con códigos diferentes.

El módulo deberá priorizar:

- Integridad de datos.
- Consulta rápida.
- Identificación visual.
- Conservación histórica.
- Preparación para futuras reservas.
- Control de acceso mediante roles.

---

# 2. Technology

La implementación utilizará el stack existente del proyecto:

- Laravel 12.
- PHP 8.3+.
- MySQL.
- Blade.
- Bootstrap 5.
- JavaScript.
- Laravel Storage.
- Spatie Laravel Permission.
- PHPUnit Feature Tests.

No se incorporarán dependencias adicionales si Laravel proporciona la funcionalidad necesaria.

---

# 3. Domain entities

El módulo incorporará las siguientes entidades principales:

- Category.
- Product.
- ProductPhoto.

También utilizará dos enums de dominio:

- ProductStatus.
- ProductCondition.

---

# 4. Category entity

La tabla:

categories

contendrá:

- id
- name
- created_at
- updated_at

Restricciones:

- name obligatorio.
- name único.
- No podrá eliminarse una categoría que tenga productos relacionados.

Relación:

Category
1
→
N Products

Una categoría podrá tener muchos productos.

Cada producto pertenecerá obligatoriamente a una categoría.

---

# 5. Product entity

La tabla:

products

contendrá:

- id
- category_id
- code
- name
- description
- size
- base_price
- condition
- detail_description
- status
- created_at
- updated_at

## Campos

### category_id

Clave foránea hacia:

categories.id

Será obligatoria.

La base de datos deberá impedir eliminar una categoría mientras existan productos relacionados.

---

### code

Código interno único del producto.

Ejemplo:

R025

Restricciones:

- Obligatorio.
- String.
- Único.

La unicidad deberá existir tanto en validación HTTP como mediante índice UNIQUE en MySQL.

---

### name

Nombre comercial o descriptivo.

Ejemplo:

Vestido rojo.

Será obligatorio.

---

### description

Descripción general opcional.

---

### size

Talla opcional.

Ejemplos:

6-9 meses

M

38

Única

Se almacenará como texto para permitir distintos tipos de productos.

---

### base_price

Precio comercial de referencia.

Se almacenará utilizando:

DECIMAL(10,2)

No se utilizarán FLOAT ni DOUBLE para dinero.

Será obligatorio y deberá ser:

>= 0.

---

### condition

Representará el estado físico del producto.

Valores permitidos:

- NUEVO_SIN_USO
- COMO_NUEVO
- BUEN_ESTADO
- CON_DETALLES

---

### detail_description

Permitirá describir defectos, marcas u observaciones físicas.

Será nullable.

Cuando:

condition = CON_DETALLES

la interfaz deberá solicitar esta información.

---

### status

Representará el estado operativo.

Valores:

- DISPONIBLE
- RESERVADO
- VENDIDO
- INACTIVO

---

# 6. ProductStatus enum

Se creará:

app/Enums/ProductStatus.php

Valores:

DISPONIBLE
RESERVADO
VENDIDO
INACTIVO

Ejemplo conceptual:

```php
enum ProductStatus: string
{
    case AVAILABLE = 'DISPONIBLE';
    case RESERVED = 'RESERVADO';
    case SOLD = 'VENDIDO';
    case INACTIVE = 'INACTIVO';
}

7. ProductCondition enum

Se creará:

app/Enums/ProductCondition.php

Valores:

NUEVO_SIN_USO
COMO_NUEVO
BUEN_ESTADO
CON_DETALLES

Ejemplo conceptual:

enum ProductCondition: string
{
    case NEW_UNUSED = 'NUEVO_SIN_USO';
    case LIKE_NEW = 'COMO_NUEVO';
    case GOOD_CONDITION = 'BUEN_ESTADO';
    case WITH_DETAILS = 'CON_DETALLES';
}
8. Estados permitidos desde Catalog Management

Aunque ProductStatus incluye:

DISPONIBLE
RESERVADO
VENDIDO
INACTIVO

el módulo administrativo de catálogo no deberá permitir establecer libremente estados pertenecientes a otros procesos de negocio.

Durante esta Spec:

Al crear un producto:

DISPONIBLE
INACTIVO

serán los estados seleccionables.

RESERVADO será gestionado posteriormente por el módulo de reservas.

VENDIDO será gestionado posteriormente cuando una venta sea completada.

Esto evita que un usuario marque manualmente una prenda como RESERVADA o VENDIDA sin existir la operación correspondiente.

El catálogo podrá consultar y mostrar cualquiera de los cuatro estados.

9. ProductPhoto entity

La tabla:

product_photos

contendrá:

id
product_id
path
is_primary
created_at
updated_at

Relación:

Product
1
→
N ProductPhoto

Cada producto deberá tener al menos una fotografía después de ser creado correctamente.

10. Fotografías

Las fotografías se almacenarán utilizando Laravel Storage.

Disco inicial:

public

Ubicación conceptual:

products/{product_id}/

Ejemplo:

storage/app/public/products/15/example.webp

Los archivos públicos serán servidos mediante:

public/storage

utilizando:

php artisan storage:link

si todavía no existe el enlace.

11. Formatos de imagen

Formatos permitidos inicialmente:

jpg
jpeg
png
webp

Tamaño máximo inicial por fotografía:

4 MB.

Validación conceptual:

'image',
'mimes:jpg,jpeg,png,webp',
'max:4096'

No se utilizará el nombre original como nombre físico obligatorio del archivo.

Laravel podrá generar un nombre único.

12. Fotografía principal

Cada producto deberá poder identificar una fotografía principal.

Campo:

is_primary

Reglas:

Solo una fotografía por producto deberá ser principal.
La primera fotografía cargada al crear el producto será principal automáticamente.
El Administrador podrá seleccionar posteriormente otra fotografía como principal.
Al cambiar la fotografía principal, las demás fotografías del mismo producto deberán quedar con is_primary = false.
No podrá establecerse como principal una fotografía perteneciente a otro producto.
13. Eliminación de fotografías

El Administrador podrá eliminar fotografías individuales.

Al eliminar:

Se eliminará el registro ProductPhoto.
Se eliminará el archivo correspondiente del Storage.

No deberá permitirse eliminar la última fotografía de un producto.

Si se elimina la fotografía principal y existen otras fotografías:

otra fotografía deberá convertirse en principal.

La operación deberá mantener un producto con al menos una fotografía.

14. Category model

Se creará:

app/Models/Category.php

Relación:

public function products()
{
    return $this->hasMany(Product::class);
}

fillable:

name
15. Product model

Se creará:

app/Models/Product.php

Relaciones:

Product belongsTo Category
Product hasMany ProductPhoto

Casts:

base_price → decimal:2
condition → ProductCondition
status → ProductStatus

fillable:

category_id
code
name
description
size
base_price
condition
detail_description
status

No se utilizará soft delete durante esta Spec.

Los productos no tendrán una operación destructiva ordinaria.

Para retirarlos del catálogo operativo se utilizará:

INACTIVO.

16. ProductPhoto model

Se creará:

app/Models/ProductPhoto.php

Relación:

ProductPhoto belongsTo Product.

fillable:

product_id
path
is_primary

Cast:

is_primary → boolean.

17. Category requests

Se crearán:

app/Http/Requests/StoreCategoryRequest.php

app/Http/Requests/UpdateCategoryRequest.php

Validaciones principales:

Crear

name:

required
string
max:100
unique:categories,name
Actualizar

name:

required
string
max:100
unique ignorando la categoría actual
18. Product requests

Se crearán:

app/Http/Requests/StoreProductRequest.php

app/Http/Requests/UpdateProductRequest.php

StoreProductRequest

Validará:

category_id:

required
exists:categories,id

code:

required
string
max:50
unique:products,code

name:

required
string
max:150

description:

nullable
string

size:

nullable
string
max:50

base_price:

required
numeric
min:0
decimal:0,2

condition:

required
valor perteneciente a ProductCondition

detail_description:

nullable
string

status:

required
únicamente DISPONIBLE o INACTIVO desde este módulo

images:

required
array
min:1

images.*:

image
mimes:jpg,jpeg,png,webp
max:4096
UpdateProductRequest

Aplicará reglas similares.

Diferencias:

El código único ignorará el producto actual.
Las nuevas fotografías serán opcionales.
No será necesario volver a cargar fotografías existentes.
El producto deberá conservar al menos una fotografía después de cualquier operación.
19. Validación de CON_DETALLES

Cuando:

condition = CON_DETALLES

detail_description deberá ser obligatorio.

Para los demás estados podrá ser:

nullable.

La validación deberá realizarse en el servidor.

20. CategoryController

Se creará:

app/Http/Controllers/CategoryController.php

Responsabilidades:

index
create
store
edit
update
destroy

destroy solo podrá completarse cuando:

category.products()->doesntExist()

Si existen productos:

la operación será rechazada con un mensaje comprensible.

La restricción de clave foránea también protegerá la base de datos.

21. ProductController

Se creará:

app/Http/Controllers/ProductController.php

Responsabilidades:

index
show
create
store
edit
update
deactivate

No existirá:

destroy()

para productos durante el flujo ordinario.

22. ProductPhotoController

Se creará:

app/Http/Controllers/ProductPhotoController.php

Responsabilidades:

destroy
setPrimary

Solo Administrador podrá ejecutar estas operaciones.

23. Actions

Las operaciones que afecten simultáneamente producto, fotografías y almacenamiento se separarán de los controladores.

Se crearán inicialmente:

app/Actions/Products/CreateProductAction.php

app/Actions/Products/UpdateProductAction.php

app/Actions/Products/DeleteProductPhotoAction.php

app/Actions/Products/SetPrimaryProductPhotoAction.php

Los controladores deberán permanecer enfocados en:

Recibir la solicitud.
Ejecutar autorización mediante middleware.
Invocar la operación correspondiente.
Redirigir.
Mostrar mensajes.
24. CreateProductAction

Responsabilidades:

Crear Product.
Guardar fotografías.
Crear ProductPhoto.
Marcar la primera fotografía como principal.
Mantener consistencia si ocurre una excepción.

La creación de registros de base de datos deberá utilizar una transacción.

Si se almacenaron archivos y posteriormente falla la operación:

los archivos creados deberán limpiarse para evitar archivos huérfanos.

25. UpdateProductAction

Responsabilidades:

Actualizar campos permitidos.
Mantener el mismo Product.id.
Agregar nuevas fotografías cuando corresponda.
No eliminar fotografías existentes automáticamente.
Mantener created_at.
Mantener consistencia ante errores.
26. DeleteProductPhotoAction

Antes de eliminar una fotografía deberá comprobar:

La fotografía pertenece al producto indicado.
El producto posee más de una fotografía.

Si solo existe una:

la operación deberá rechazarse.

Si la fotografía eliminada era principal:

otra fotografía del producto deberá quedar como principal.

27. SetPrimaryProductPhotoAction

La operación deberá:

Comprobar pertenencia de la fotografía al producto.
Desmarcar fotografías principales anteriores.
Marcar la fotografía seleccionada.
Ejecutarse dentro de una transacción.
28. Búsqueda y filtros

ProductController@index aceptará parámetros GET.

Inicialmente:

search
category
status
search

Buscará coincidencias por:

code
name
category

Filtrará por:

category_id.

status

Filtrará por:

ProductStatus.

Los filtros deberán poder combinarse.

Ejemplo:

/products?search=vestido&category=2&status=DISPONIBLE
29. Paginación

El listado de productos utilizará:

25 productos por página.

Los parámetros de búsqueda deberán conservarse al cambiar de página mediante:

->withQueryString()

El proyecto ya utiliza paginación Bootstrap 5.

30. Orden del catálogo

El listado se ordenará inicialmente por:

created_at DESC.

Los productos registrados más recientemente aparecerán primero.

31. Rutas compartidas

Dentro de:

auth + active

Administrador y Vendedor podrán acceder a:

GET /products
GET /products/{product}

Nombres:

products.index
products.show
32. Rutas administrativas de productos

Dentro de:

auth + active + role:Administrador

se permitirán:

GET    /products/create
POST   /products
GET    /products/{product}/edit
PUT    /products/{product}
PATCH  /products/{product}/deactivate
DELETE /products/{product}/photos/{photo}
PATCH  /products/{product}/photos/{photo}/primary
33. Rutas de categorías

Las categorías serán administradas únicamente por Administrador.

Rutas:

GET    /categories
GET    /categories/create
POST   /categories
GET    /categories/{category}/edit
PUT    /categories/{category}
DELETE /categories/{category}

Todas estarán protegidas mediante:

auth

active

role:Administrador

34. Route model binding

Laravel Route Model Binding será utilizado para:

Category
Product
ProductPhoto

La pertenencia de ProductPhoto a Product deberá validarse explícitamente.

No deberá asumirse únicamente porque ambos IDs estén presentes en la URL.

35. Views

Se crearán:

resources/views/categories/index.blade.php
resources/views/categories/create.blade.php
resources/views/categories/edit.blade.php

resources/views/products/index.blade.php
resources/views/products/show.blade.php
resources/views/products/create.blade.php
resources/views/products/edit.blade.php

Todas reutilizarán:

resources/views/layouts/app.blade.php

36. Categories UI

El listado mostrará:

Nombre.
Cantidad de productos.
Fecha de creación.
Acciones.

Administrador podrá:

Crear.
Editar.
Eliminar cuando sea permitido.
37. Products UI

El listado mostrará:

Fotografía principal.
Código.
Nombre.
Categoría.
Talla.
Precio base.
Estado de conservación.
Estado operativo.
Acciones permitidas.

Administrador verá:

Ver.
Editar.
Desactivar.

Vendedor verá:

Ver.
38. Product detail UI

products.show deberá mostrar:

Código.
Nombre.
Categoría.
Descripción.
Talla.
Precio base.
Estado de conservación.
Detalles.
Estado operativo.
Fotografía principal.
Galería de fotografías.

El Administrador podrá acceder desde allí a la edición.

39. Visualización de estados

Los estados deberán diferenciarse mediante badges de Bootstrap.

No se dependerá únicamente del color.

Siempre se mostrará también el texto:

DISPONIBLE

RESERVADO

VENDIDO

INACTIVO

Lo mismo se aplicará al estado de conservación.

40. Navegación

El layout principal añadirá acceso a:

Productos

para:

Administrador.
Vendedor.

Categorías aparecerá solamente para:

Administrador.

41. Seguridad

Todas las operaciones críticas serán validadas en el servidor.

No se confiará únicamente en:

Botones ocultos.
JavaScript.
Campos select del navegador.

Un Vendedor que intente acceder directamente a una ruta administrativa deberá recibir:

403 Forbidden.

Un usuario no autenticado será enviado al login.

Un usuario inactivo será rechazado mediante el middleware existente.

42. Database constraints

La base de datos deberá incluir como mínimo:

categories.name UNIQUE

products.code UNIQUE

products.category_id FOREIGN KEY

product_photos.product_id FOREIGN KEY

La eliminación de una categoría utilizada deberá quedar restringida.

La eliminación de un Product deberá cascader fotografías únicamente si alguna operación administrativa futura eliminara físicamente el producto.

El flujo ordinario del MVP no eliminará productos.

43. Factories

Se crearán:

CategoryFactory

ProductFactory

ProductPhotoFactory cuando resulte útil para tests.

ProductFactory deberá generar por defecto:

status = DISPONIBLE.

El estado de conservación podrá generarse con un valor permitido.

44. Testing strategy

Se utilizarán PHPUnit Feature Tests.

Los tests utilizarán:

RefreshDatabase.

Para fotografías:

Storage::fake('public').

No deberán modificar los archivos reales del desarrollador.

45. Category tests

Se comprobará como mínimo:

Admin puede ver categorías.
Admin puede crear categoría.
Nombre obligatorio.
Nombre único.
Admin puede editar.
No puede eliminar categoría con productos.
Puede eliminar categoría sin productos.
Vendedor recibe 403.
Invitado es redirigido.
46. Product tests

Se comprobará como mínimo:

Admin puede crear producto.
Código único.
Categoría existente.
Precio no negativo.
Estado válido.
Estado de conservación válido.
CON_DETALLES exige descripción.
Producto exige una fotografía.
Primera fotografía queda como principal.
Admin puede editar.
created_at no cambia.
Vendedor no puede crear.
Vendedor no puede editar.
Vendedor puede consultar.
Invitado es redirigido.
47. Photo tests

Se comprobará:

Puede cargar varias fotografías.
No acepta archivos no válidos.
No puede eliminar la última fotografía.
Puede eliminar una fotografía cuando existen otras.
Archivo eliminado desaparece del Storage.
Puede cambiar fotografía principal.
Solo queda una fotografía principal.
No puede manipular una fotografía perteneciente a otro producto.
48. Search and filter tests

Se comprobará:

Buscar por código.
Buscar por nombre.
Filtrar por categoría.
Filtrar por estado.
Combinar filtros.
Paginar resultados.
49. Authorization tests

Se comprobará:

Administrador:

CRUD de categorías.
Crear y editar productos.
Gestionar fotografías.

Vendedor:

Consultar catálogo.
Consultar detalle.

Vendedor no podrá:

Crear categorías.
Editar categorías.
Crear productos.
Editar productos.
Gestionar fotografías.
50. Out of scope

Esta Spec no implementará:

Sesiones Live.
Precio Live.
Reservas.
Pedido.
Precio acordado.
Pagos.
Entregas.
Stock múltiple.
Historial avanzado de cambios de precio.
Importación masiva.
Códigos de barras.
Integración TikTok.
Catálogo público.

Estas funcionalidades pertenecen a Specs posteriores.

51. Future integration

El módulo deberá dejar preparado que posteriormente:

Producto DISPONIBLE

pueda asociarse a un Live.

Cuando se cree una reserva:

DISPONIBLE → RESERVADO.

Cuando una reserva sea cancelada o expirada:

RESERVADO → DISPONIBLE.

Cuando una venta sea completada:

RESERVADO → VENDIDO.

Estas transiciones no serán implementadas en Catalog Management excepto la visualización de su estado actual.

52. Design principles

La implementación deberá seguir:

Controllers pequeños.
Form Requests para validación.
Actions para operaciones complejas.
Eloquent para relaciones.
Enums para estados definidos.
Transacciones cuando varias entidades deban cambiar juntas.
Restricciones de base de datos como segunda línea de defensa.
Bootstrap 5 para interfaz.
Feature Tests para comportamiento observable.
No eliminar historial comercial innecesariamente.
