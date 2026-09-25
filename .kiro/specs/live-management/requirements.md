# Requirements Document - Live Management

## Introduction

Live Management permitirá preparar, iniciar, administrar y finalizar las transmisiones de venta realizadas por el negocio mediante TikTok Live.

Una sesión Live agrupará productos del catálogo y permitirá definir un precio específico para cada producto durante esa transmisión.

Este módulo será la base del futuro Modo Live, donde se registrarán compradores, reservas y expresiones como "Mío".

Esta Spec no implementará todavía reservas ni pedidos.

---

# 1. Sesiones Live

## User Story

Como Administrador o Vendedor,
quiero registrar una sesión Live,
para preparar los productos que serán ofrecidos durante una transmisión.

## Acceptance Criteria

1. WHEN se cree un Live THEN el sistema SHALL exigir un nombre o título.

2. THE Live SHALL poder incluir una fecha y hora programada.

3. THE fecha programada SHALL ser opcional.

4. THE Live SHALL permitir notas opcionales.

5. WHEN se cree un Live THEN su estado inicial SHALL ser PROGRAMADO.

6. THE sistema SHALL registrar created_at y updated_at.

---

# 2. Estados de Live

Los estados permitidos serán:

- PROGRAMADO
- ACTIVO
- FINALIZADO
- CANCELADO

## Acceptance Criteria

1. WHEN se cree un Live THEN SHALL iniciar como PROGRAMADO.

2. WHEN se inicie un Live PROGRAMADO THEN SHALL cambiar a ACTIVO.

3. WHEN se finalice un Live ACTIVO THEN SHALL cambiar a FINALIZADO.

4. WHEN un Live PROGRAMADO o ACTIVO sea cancelado THEN SHALL cambiar a CANCELADO.

5. A FINALIZADO Live SHALL NOT regresar a ACTIVO.

6. A CANCELADO Live SHALL NOT regresar a ACTIVO.

7. THE estado SHALL almacenarse utilizando valores definidos por el dominio.

---

# 3. Un único Live activo

## User Story

Como operador,
quiero evitar tener varias transmisiones activas simultáneamente,
para que el Modo Live tenga un contexto claro.

## Acceptance Criteria

1. THE MVP SHALL permitir como máximo un Live ACTIVO simultáneamente.

2. WHEN exista un Live ACTIVO THEN otro Live PROGRAMADO SHALL NOT poder iniciarse.

3. WHEN el Live activo sea FINALIZADO o CANCELADO THEN otro Live SHALL poder iniciarse.

4. THE validación SHALL realizarse en servidor.

---

# 4. Productos de un Live

## User Story

Como Vendedor,
quiero asociar productos del catálogo a una transmisión,
para preparar los artículos que serán ofrecidos.

## Acceptance Criteria

1. A Live SHALL poder tener múltiples productos.

2. A Product SHALL poder aparecer en diferentes Lives a lo largo del tiempo.

3. THE misma combinación Live + Product SHALL NOT duplicarse.

4. WHEN un producto sea añadido a un Live THEN SHALL continuar siendo un Product existente del catálogo.

5. Añadir un producto a un Live SHALL NOT cambiar automáticamente su estado a RESERVADO.

6. Solo productos DISPONIBLE SHALL poder añadirse inicialmente a un Live.

7. Productos INACTIVO, RESERVADO o VENDIDO SHALL NOT poder añadirse como nuevos productos del Live.

---

# 5. Precio del Live

## User Story

Como Vendedor,
quiero definir un precio para cada producto durante un Live,
para venderlo a un precio diferente al precio base cuando sea necesario.

## Acceptance Criteria

1. EVERY Product asociado a un Live SHALL tener live_price.

2. live_price SHALL ser mayor o igual a 0.

3. live_price SHALL utilizar máximo dos decimales.

4. THE precio base del producto SHALL permanecer sin cambios.

5. THE interfaz MAY utilizar base_price como valor sugerido inicialmente.

6. WHEN live_price sea modificado THEN SHALL modificarse únicamente el precio asociado a ese Live.

7. El futuro agreed_price de una reserva SHALL ser independiente de live_price.

---

# 6. Agregar productos

## User Story

Como Administrador o Vendedor,
quiero agregar productos disponibles al Live,
para preparar la transmisión.

## Acceptance Criteria

1. WHEN un Live esté PROGRAMADO THEN SHALL permitir agregar productos.

2. WHEN un Live esté ACTIVO THEN SHALL permitir agregar productos durante la transmisión.

3. WHEN un Live esté FINALIZADO THEN SHALL NOT permitir agregar productos.

4. WHEN un Live esté CANCELADO THEN SHALL NOT permitir agregar productos.

5. WHEN el producto ya pertenezca al Live THEN SHALL rechazarse la duplicación.

6. WHEN el producto no esté DISPONIBLE THEN SHALL rechazarse.

---

# 7. Retirar productos del Live

## User Story

Como operador,
quiero retirar un producto de un Live,
para corregir la lista de productos ofrecidos.

## Acceptance Criteria

1. WHEN el Live esté PROGRAMADO THEN SHALL permitirse retirar productos.

2. WHEN el Live esté ACTIVO THEN SHALL permitirse retirar productos mientras no exista una restricción futura por reserva.

3. WHEN el Live esté FINALIZADO THEN SHALL conservarse su historial de productos.

4. WHEN el Live esté CANCELADO THEN SHALL conservarse su historial.

5. Retirar un producto del Live SHALL NOT eliminar el Product del catálogo.

6. Esta Spec no implementará todavía restricciones derivadas de Reservation porque ese módulo aún no existe.

---

# 8. Edición del precio Live

## User Story

Como Vendedor,
quiero modificar el precio del producto dentro de un Live,
para adaptarlo durante la transmisión.

## Acceptance Criteria

1. WHEN el Live esté PROGRAMADO THEN SHALL poder actualizarse live_price.

2. WHEN el Live esté ACTIVO THEN SHALL poder actualizarse live_price.

3. WHEN el Live esté FINALIZADO THEN SHALL NOT poder modificarse.

4. WHEN el Live esté CANCELADO THEN SHALL NOT poder modificarse.

5. Modificar live_price SHALL NOT cambiar products.base_price.

---

# 9. Consulta de Live

## User Story

Como usuario interno,
quiero consultar una sesión Live,
para ver su información y productos.

## Acceptance Criteria

1. THE detalle SHALL mostrar:

   - Nombre.
   - Estado.
   - Fecha programada.
   - Inicio real.
   - Finalización.
   - Notas.

2. THE detalle SHALL mostrar los productos asociados.

3. EACH producto SHALL mostrar como mínimo:

   - Código.
   - Nombre.
   - Estado actual.
   - Precio base.
   - Precio Live.

4. THE Live SHALL conservar los productos asociados después de FINALIZADO.

---

# 10. Inicio del Live

## User Story

Como Vendedor,
quiero iniciar una sesión Live,
para indicar que la transmisión está actualmente en curso.

## Acceptance Criteria

1. ONLY un Live PROGRAMADO SHALL poder iniciarse.

2. WHEN se inicie THEN status SHALL cambiar a ACTIVO.

3. WHEN se inicie THEN started_at SHALL registrar la fecha y hora actual.

4. WHEN otro Live esté ACTIVO THEN la operación SHALL ser rechazada.

5. THE cambio SHALL ejecutarse de manera segura en base de datos.

---

# 11. Finalización del Live

## User Story

Como Vendedor,
quiero finalizar el Live,
para indicar que la transmisión terminó.

## Acceptance Criteria

1. ONLY un Live ACTIVO SHALL poder finalizarse.

2. WHEN se finalice THEN status SHALL cambiar a FINALIZADO.

3. WHEN se finalice THEN ended_at SHALL registrar fecha y hora actual.

4. Finalizar un Live SHALL NOT eliminar sus productos.

5. En el futuro, finalizar un Live SHALL NOT cancelar automáticamente reservas o pedidos existentes.

6. Esta Spec no implementará todavía lógica de Reservation.

---

# 12. Cancelación del Live

## User Story

Como operador,
quiero cancelar una transmisión,
para registrar Lives que finalmente no se realizaron o debieron detenerse.

## Acceptance Criteria

1. A PROGRAMADO Live SHALL poder cancelarse.

2. A ACTIVO Live SHALL poder cancelarse.

3. A FINALIZADO Live SHALL NOT poder cancelarse.

4. A CANCELADO Live SHALL NOT poder cancelarse nuevamente.

5. WHEN un Live sea cancelado THEN SHALL conservarse en el sistema.

6. WHEN un Live ACTIVO sea cancelado THEN ended_at SHALL registrar el momento de cancelación.

7. Cancelar un Live SHALL NOT eliminar productos relacionados.

---

# 13. Edición de información

## User Story

Como operador,
quiero editar información de una transmisión programada,
para corregir su planificación.

## Acceptance Criteria

1. A PROGRAMADO Live SHALL poder editar:

   - Nombre.
   - Fecha programada.
   - Notas.

2. A ACTIVO Live SHALL conservar su información principal.

3. A FINALIZADO Live SHALL conservar su información histórica.

4. A CANCELADO Live SHALL conservar su información histórica.

5. created_at SHALL NOT modificarse.

---

# 14. Listado

## User Story

Como Administrador o Vendedor,
quiero consultar los Lives,
para conocer transmisiones actuales y anteriores.

## Acceptance Criteria

1. THE sistema SHALL mostrar los Lives registrados.

2. THE listado SHALL mostrar:

   - Nombre.
   - Estado.
   - Fecha programada.
   - Inicio.
   - Cantidad de productos.

3. THE sistema SHALL permitir filtrar por estado.

4. THE sistema SHALL mostrar primero los registros más recientes.

5. THE listado SHALL paginar 25 registros.

---

# 15. Permisos

## Acceptance Criteria

1. Administrador SHALL poder:

   - Listar.
   - Crear.
   - Consultar.
   - Editar.
   - Iniciar.
   - Finalizar.
   - Cancelar.
   - Gestionar productos.

2. Vendedor SHALL poder realizar las mismas operaciones operativas del Live.

3. Invitados SHALL ser redirigidos al login.

4. Usuarios inactivos SHALL quedar protegidos mediante middleware active.

5. No existirán rutas públicas del Live Management.

---

# 16. Conservación histórica

## Acceptance Criteria

1. Live Management SHALL NOT implementar eliminación física ordinaria.

2. NO SHALL existir acción destroy para LiveSession.

3. Lives FINALIZADO y CANCELADO SHALL permanecer consultables.

4. Las asociaciones históricas con productos SHALL conservarse.

---

# 17. Preparación para Modo Live

## Acceptance Criteria

1. THE sistema SHALL permitir localizar el Live ACTIVO.

2. THE sistema SHALL permitir consultar rápidamente sus productos.

3. THE sistema SHALL conservar live_price por producto.

4. THE diseño SHALL permitir que Reservation se relacione posteriormente con:

   - Live.
   - Product.
   - Customer.

5. Solo un Live ACTIVO SHALL proporcionar contexto al futuro Modo Live.

---

# 18. Fuera de alcance

Esta Spec NO implementará:

- Reservas.
- "Mío".
- agreed_price.
- Pedidos.
- Pagos.
- Confirmación por WhatsApp.
- Plazo de 48 horas.
- Recordatorios.
- Entregas.
- Beneficio de paquetería.
- Integración directa con TikTok.
- Lectura automática de comentarios.
