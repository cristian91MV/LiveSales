# Requirements Document

## Introduction

El módulo Catalog Management de LiveSales permitirá administrar las categorías y productos que posteriormente serán utilizados durante las sesiones Live.

Durante el MVP cada producto representa una única unidad física vendible.

El módulo deberá permitir mantener un catálogo interno confiable, identificar rápidamente cada producto mediante un código único, conservar sus fotografías, precio base, estado de conservación y estado operativo.

Esta funcionalidad será utilizada posteriormente por el módulo de Sesiones Live y por el Modo Live.

---

# 1. Gestión de categorías

## User Story

Como Administrador,
quiero administrar las categorías de productos,
para organizar el catálogo utilizado en LiveSales.

## Acceptance Criteria

1. WHEN un Administrador accede al módulo de categorías THEN el sistema SHALL mostrar las categorías registradas.

2. WHEN el Administrador crea una categoría con datos válidos THEN el sistema SHALL almacenar la categoría.

3. WHEN se registra una categoría THEN el sistema SHALL exigir un nombre.

4. WHEN se intenta registrar otra categoría con el mismo nombre THEN el sistema SHALL rechazar la operación.

5. WHEN el Administrador modifica una categoría THEN el sistema SHALL conservar el mismo registro y actualizar únicamente sus datos permitidos.

6. WHEN una categoría tiene productos relacionados THEN el sistema SHALL impedir una eliminación que deje referencias inválidas.

7. WHEN un usuario con rol Vendedor intenta acceder a las funciones administrativas de categorías THEN el sistema SHALL rechazar el acceso.

---

# 2. Listado de productos

## User Story

Como Administrador o Vendedor,
quiero consultar los productos registrados,
para conocer rápidamente su información y disponibilidad.

## Acceptance Criteria

1. WHEN un usuario autorizado accede al catálogo THEN el sistema SHALL mostrar los productos registrados.

2. WHEN se muestra un producto THEN el sistema SHALL presentar como mínimo:

   - Código.
   - Nombre.
   - Categoría.
   - Precio base.
   - Estado de conservación.
   - Estado operativo.

3. WHEN existan muchos productos THEN el sistema SHALL utilizar paginación.

4. WHEN el usuario busque mediante código THEN el sistema SHALL poder localizar el producto correspondiente.

5. WHEN el usuario busque mediante nombre THEN el sistema SHALL mostrar productos coincidentes.

6. WHEN se filtre por estado operativo THEN el sistema SHALL mostrar únicamente productos correspondientes al estado seleccionado.

7. WHEN se filtre por categoría THEN el sistema SHALL mostrar únicamente productos pertenecientes a esa categoría.

---

# 3. Registro de producto

## User Story

Como Administrador,
quiero registrar una unidad física como producto,
para utilizarla posteriormente durante un Live.

## Acceptance Criteria

1. WHEN el Administrador registra un producto THEN el sistema SHALL exigir:

   - Código único.
   - Nombre.
   - Categoría.
   - Precio base.
   - Estado de conservación.
   - Estado operativo.

2. WHEN se registra un producto THEN el sistema SHALL exigir al menos una fotografía válida.

3. WHEN el producto tenga información adicional THEN el sistema SHALL permitir registrar:

   - Descripción.
   - Talla.
   - Descripción de detalles.
   
4. WHEN otro producto ya utiliza el mismo código THEN el sistema SHALL rechazar el registro.

5. WHEN el precio base sea registrado THEN el sistema SHALL exigir un valor numérico mayor o igual a cero.

6. WHEN se almacene dinero THEN el sistema SHALL utilizar una representación decimal adecuada y no números de punto flotante.

7. WHEN se registra un producto THEN el sistema SHALL asociarlo a una categoría existente.

8. WHEN el estado operativo no sea válido THEN el sistema SHALL rechazar el registro.

9. WHEN el estado de conservación no sea válido THEN el sistema SHALL rechazar el registro.

10. WHEN los datos sean válidos THEN el sistema SHALL crear exactamente un registro que represente una única unidad física.

---

# 4. Estados operativos del producto

## User Story

Como usuario del sistema,
quiero conocer el estado operativo de cada producto,
para evitar utilizar prendas que no están disponibles.

## Acceptance Criteria

1. THE sistema SHALL reconocer únicamente los siguientes estados operativos:

   - DISPONIBLE
   - RESERVADO
   - VENDIDO
   - INACTIVO

2. WHEN un producto se crea manualmente desde el módulo de productos THEN el Administrador SHALL poder establecer únicamente un estado permitido por las reglas del módulo.

3. WHEN un producto se encuentre VENDIDO THEN el sistema SHALL conservarlo históricamente.

4. WHEN un producto se encuentre INACTIVO THEN el sistema SHALL conservarlo históricamente.

5. WHEN se consulte el catálogo THEN el sistema SHALL diferenciar visualmente los distintos estados.

6. WHEN otros módulos modifiquen posteriormente el estado del producto THEN este módulo SHALL reflejar el estado actual almacenado.

---

# 5. Estado de conservación

## User Story

Como vendedor,
quiero registrar la condición física del producto,
para conocer y comunicar correctamente su estado durante el Live.

## Acceptance Criteria

1. THE sistema SHALL aceptar únicamente:

   - NUEVO_SIN_USO
   - COMO_NUEVO
   - BUEN_ESTADO
   - CON_DETALLES

2. WHEN el producto se registre como CON_DETALLES THEN el sistema SHALL permitir describir los detalles o defectos.

3. WHEN se modifique el estado de conservación THEN el sistema SHALL actualizar la información del producto sin modificar automáticamente su precio.

4. THE estado de conservación SHALL ser independiente del estado operativo del producto.

---

# 6. Precio base

## User Story

Como Administrador,
quiero registrar un precio base,
para tener una referencia comercial antes de realizar una negociación durante un Live.

## Acceptance Criteria

1. WHEN se registra un producto THEN el precio base SHALL ser obligatorio.

2. WHEN se ingresa un precio negativo THEN el sistema SHALL rechazarlo.

3. WHEN se modifica el precio base THEN la modificación SHALL afectar únicamente al producto.

4. WHEN existan pedidos históricos en futuras funcionalidades THEN modificar el precio base SHALL NOT modificar los precios históricos almacenados en esos pedidos.

5. THE precio base SHALL ser únicamente una referencia comercial.

---

# 7. Fotografías

## User Story

Como Administrador,
quiero registrar fotografías de cada producto,
para que los vendedores puedan identificar visualmente la unidad física durante el Live.

## Acceptance Criteria

1. WHEN se registren fotografías THEN el sistema SHALL asociarlas al producto correspondiente.

2. WHEN un producto tenga varias fotografías THEN el sistema SHALL conservar todas las fotografías válidas asociadas.

3. WHEN se elimine una fotografía THEN el sistema SHALL eliminar la asociación correspondiente sin eliminar el producto.

4. WHEN se cargue un archivo no permitido THEN el sistema SHALL rechazarlo.

5. WHEN se cargue una imagen válida THEN el sistema SHALL almacenarla utilizando el almacenamiento configurado por Laravel.

6. WHEN el producto sea mostrado THEN el sistema SHALL poder utilizar una fotografía principal para facilitar su identificación.

---

# 8. Edición de producto

## User Story

Como Administrador,
quiero modificar los datos de un producto,
para mantener actualizada la información del catálogo.

## Acceptance Criteria

1. WHEN el Administrador edita un producto THEN el sistema SHALL permitir modificar:

   - Código.
   - Nombre.
   - Descripción.
   - Categoría.
   - Talla.
   - Precio base.
   - Estado de conservación.
   - Descripción de detalles.
   - Fotografías permitidas.

2. WHEN el código sea modificado THEN el sistema SHALL mantener su unicidad.

3. WHEN el producto ya tenga información histórica relacionada en futuras funcionalidades THEN la edición SHALL conservar la identidad del producto.

4. WHEN se edite un producto THEN created_at SHALL permanecer sin cambios.

5. WHEN se edite un producto THEN updated_at SHALL reflejar la modificación.

---

# 9. Eliminación y conservación histórica

## User Story

Como Administrador,
quiero retirar productos sin destruir información histórica,
para mantener consistencia en LiveSales.

## Acceptance Criteria

1. WHEN un producto deba dejar de utilizarse THEN el sistema SHALL permitir marcarlo INACTIVO.

2. WHEN un producto esté relacionado con operaciones del negocio THEN el sistema SHALL NOT eliminarlo físicamente mediante el flujo ordinario.

3. WHEN un producto sea VENDIDO THEN el sistema SHALL conservar su información.

4. WHEN un producto sea INACTIVO THEN el sistema SHALL continuar permitiendo su consulta administrativa.

5. THE aplicación SHALL priorizar cambios de estado frente a eliminaciones destructivas.

---

# 10. Permisos

## User Story

Como propietario del negocio,
quiero limitar las acciones sobre el catálogo según el rol,
para evitar modificaciones no autorizadas.

## Acceptance Criteria

1. WHEN un Administrador acceda a categorías THEN SHALL poder administrarlas.

2. WHEN un Administrador acceda a productos THEN SHALL poder crear y modificar productos.

3. WHEN un Vendedor acceda al catálogo THEN SHALL poder consultar productos.

4. WHEN un Vendedor intente crear, editar o administrar productos mediante una ruta administrativa THEN el sistema SHALL responder con acceso denegado.

5. WHEN un usuario no autenticado intente acceder al catálogo interno THEN el sistema SHALL redirigirlo al login.

6. WHEN un usuario inactivo intente acceder THEN SHALL aplicarse el middleware de usuario activo existente.

---

# 11. Integridad de producto

## User Story

Como sistema,
quiero mantener datos consistentes,
para que futuras reservas y Lives puedan confiar en el catálogo.

## Acceptance Criteria

1. THE código del producto SHALL ser único.

2. THE producto SHALL pertenecer a una categoría existente.

3. THE precio base SHALL almacenarse con precisión decimal.

4. THE estado operativo SHALL corresponder a un estado definido.

5. THE estado de conservación SHALL corresponder a un estado definido.

6. THE producto SHALL representar una única unidad física.

7. THE aplicación SHALL validar los datos tanto desde solicitudes HTTP como mediante restricciones apropiadas de base de datos cuando corresponda.

---

# 12. Preparación para integración con Lives

## User Story

Como desarrollador,
quiero que el catálogo tenga reglas claras de disponibilidad,
para que posteriormente pueda integrarse correctamente con las sesiones Live.

## Acceptance Criteria

1. WHEN un producto tenga estado DISPONIBLE THEN SHALL ser candidato para asociarse a un Live.

2. WHEN un producto tenga estado VENDIDO THEN SHALL NOT considerarse disponible para nuevos Lives.

3. WHEN un producto tenga estado INACTIVO THEN SHALL NOT considerarse disponible para nuevos Lives.

4. WHEN un producto tenga estado RESERVADO THEN SHALL NOT considerarse disponible para una nueva reserva.

5. THE módulo Catalog Management SHALL NOT implementar todavía reservas, pedidos ni sesiones Live.

6. THE módulo SHALL proporcionar información suficiente para que esas funcionalidades sean implementadas en Specs posteriores.
