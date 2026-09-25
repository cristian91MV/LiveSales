# Requirements Document

## Introduction

El módulo Customer Management de LiveSales permitirá registrar, consultar y mantener la información de los compradores que participan en ventas realizadas mediante transmisiones Live.

Los clientes no tendrán cuentas de acceso al sistema.

Serán registros internos gestionados por Administradores y Vendedores.

Durante una transmisión deberá ser posible identificar rápidamente a un comprador existente o registrar uno nuevo con la menor cantidad posible de pasos.

Los principales identificadores comerciales serán:

- Nombre.
- Usuario de TikTok.
- Número de WhatsApp.

El módulo servirá posteriormente como base para:

- Sesiones Live.
- Reservas.
- Pedidos.
- Seguimiento de confirmación.
- Beneficios para clientes recurrentes.

---

# 1. Listado de clientes

## User Story

Como Administrador o Vendedor,
quiero consultar los clientes registrados,
para identificar compradores y revisar su información.

## Acceptance Criteria

1. WHEN un usuario autorizado accede al módulo de clientes THEN el sistema SHALL mostrar los clientes registrados.

2. WHEN se muestre un cliente THEN el sistema SHALL presentar como mínimo:

   - Nombre.
   - Usuario de TikTok.
   - WhatsApp.
   - Fecha de registro.

3. WHEN existan muchos clientes THEN el sistema SHALL utilizar paginación.

4. WHEN un cliente todavía no tenga WhatsApp registrado THEN el sistema SHALL mostrar claramente que el dato está pendiente.

---

# 2. Búsqueda de clientes

## User Story

Como Vendedor,
quiero buscar rápidamente un cliente,
para localizarlo durante o después de un Live.

## Acceptance Criteria

1. WHEN el usuario busque por nombre THEN el sistema SHALL mostrar clientes coincidentes.

2. WHEN el usuario busque por usuario de TikTok THEN el sistema SHALL mostrar clientes coincidentes.

3. WHEN el usuario busque por WhatsApp THEN el sistema SHALL mostrar clientes coincidentes.

4. WHEN no existan coincidencias THEN el sistema SHALL mostrar un resultado vacío sin producir error.

5. WHEN la búsqueda contenga espacios al inicio o final THEN el sistema SHALL ignorarlos.

---

# 3. Registro completo de cliente

## User Story

Como Administrador o Vendedor,
quiero registrar un cliente,
para utilizarlo posteriormente en reservas y pedidos.

## Acceptance Criteria

1. WHEN se registre un cliente THEN el nombre SHALL ser obligatorio.

2. WHEN se registre información adicional THEN el sistema SHALL permitir:

   - Usuario de TikTok.
   - WhatsApp.

3. WHEN los datos sean válidos THEN el sistema SHALL crear un único registro de cliente.

4. WHEN se crea un cliente THEN created_at y updated_at SHALL registrarse automáticamente.

---

# 4. Registro rápido durante un Live

## User Story

Como Vendedor,
quiero registrar rápidamente un comprador durante una transmisión,
para no interrumpir el flujo de ventas.

## Acceptance Criteria

1. WHEN se utilice el registro rápido THEN el sistema SHALL exigir únicamente el nombre.

2. WHEN el cliente tenga usuario de TikTok THEN SHALL poder registrarse opcionalmente.

3. WHEN todavía no se conozca el WhatsApp THEN SHALL permitirse dejarlo vacío.

4. WHEN el cliente contacte posteriormente mediante WhatsApp THEN SHALL poder completarse el dato.

5. THE diseño del módulo SHALL permitir reutilizar esta funcionalidad posteriormente dentro del Modo Live.

---

# 5. Usuario de TikTok

## User Story

Como Vendedor,
quiero registrar el usuario de TikTok del cliente,
para relacionar los comentarios del Live con una persona registrada.

## Acceptance Criteria

1. WHEN se registre un usuario de TikTok THEN el sistema SHALL permitir valores con o sin prefijo @.

2. WHEN se almacene el usuario de TikTok THEN el sistema SHALL normalizarlo para evitar diferencias innecesarias.

3. THE sistema SHALL considerar equivalentes conceptualmente:

   @marialpz

   marialpz

4. WHEN exista otro cliente con el mismo usuario TikTok normalizado THEN el sistema SHALL advertir o rechazar la duplicación según la validación definida.

5. WHEN TikTok no sea conocido THEN el campo SHALL poder permanecer null.

---

# 6. Número de WhatsApp

## User Story

Como Vendedor,
quiero registrar el WhatsApp del cliente,
para continuar el proceso de venta después del Live.

## Acceptance Criteria

1. WHEN WhatsApp todavía no sea conocido THEN SHALL poder permanecer null.

2. WHEN se registre un WhatsApp THEN el sistema SHALL aceptar únicamente caracteres válidos para un número telefónico.

3. WHEN se almacene el WhatsApp THEN el sistema SHALL normalizar el valor para facilitar búsquedas y evitar duplicados.

4. WHEN otro cliente utilice el mismo WhatsApp normalizado THEN el sistema SHALL rechazar la duplicación.

5. THE sistema SHALL permitir actualizar posteriormente un cliente que inicialmente no tenía WhatsApp.

---

# 7. Detección de posibles duplicados

## User Story

Como usuario del sistema,
quiero reducir registros duplicados de clientes,
para mantener información consistente.

## Acceptance Criteria

1. WHEN un WhatsApp ya esté registrado THEN el sistema SHALL impedir registrar otro cliente con el mismo número normalizado.

2. WHEN un usuario TikTok ya esté registrado THEN el sistema SHALL impedir registrar otro cliente con el mismo usuario normalizado.

3. WHEN únicamente coincida el nombre THEN el sistema SHALL NOT asumir automáticamente que se trata de la misma persona.

4. THE nombre no SHALL ser único.

5. THE sistema SHALL permitir que existan dos clientes con el mismo nombre cuando sus demás datos sean diferentes.

---

# 8. Edición de cliente

## User Story

Como Administrador o Vendedor,
quiero actualizar los datos de un cliente,
para completar o corregir su información.

## Acceptance Criteria

1. WHEN se edite un cliente THEN SHALL poder modificarse:

   - Nombre.
   - Usuario de TikTok.
   - WhatsApp.

2. WHEN un cliente fue registrado rápidamente sin WhatsApp THEN SHALL poder completarse posteriormente.

3. WHEN se cambie TikTok o WhatsApp THEN SHALL mantenerse la validación de unicidad.

4. WHEN se actualice el cliente THEN created_at SHALL permanecer sin cambios.

5. WHEN se actualice el cliente THEN updated_at SHALL reflejar la modificación.

---

# 9. Consulta de detalle

## User Story

Como Administrador o Vendedor,
quiero consultar el detalle de un cliente,
para revisar su información antes de gestionar una venta.

## Acceptance Criteria

1. WHEN se consulte un cliente THEN el sistema SHALL mostrar:

   - Nombre.
   - Usuario TikTok.
   - WhatsApp.
   - Fecha de registro.
   - Fecha de última actualización.

2. WHEN TikTok no exista THEN SHALL mostrarse un valor comprensible como "No registrado".

3. WHEN WhatsApp no exista THEN SHALL mostrarse un valor comprensible como "No registrado".

4. THE vista SHALL quedar preparada para mostrar posteriormente:

   - Pedidos.
   - Compras completadas.
   - Estado nuevo/recurrente.

---

# 10. Cliente nuevo y recurrente

## User Story

Como sistema,
quiero poder distinguir clientes nuevos de recurrentes,
para aplicar reglas comerciales posteriores.

## Acceptance Criteria

1. THE sistema SHALL considerar recurrente a un cliente cuando tenga al menos un pedido anterior ENTREGADO.

2. WHEN el cliente no tenga pedidos ENTREGADOS THEN SHALL considerarse nuevo.

3. WHEN un pedido esté CANCELADO THEN SHALL NOT contar como compra completada.

4. THE cálculo SHALL derivarse del historial de pedidos y no de un campo editable manualmente.

5. DURING esta Spec, como el módulo de pedidos todavía no existe, SHALL dejarse preparada la integración pero no implementarse artificialmente.

---

# 11. Eliminación y conservación histórica

## User Story

Como propietario del negocio,
quiero conservar los clientes relacionados con operaciones,
para no perder historial comercial.

## Acceptance Criteria

1. THE módulo SHALL NOT implementar eliminación física ordinaria de clientes durante el MVP.

2. WHEN un cliente exista THEN SHALL conservarse para futuras relaciones con reservas y pedidos.

3. THE sistema SHALL priorizar edición de datos frente a eliminación destructiva.

4. La eliminación avanzada o anonimización podrá definirse en una versión futura.

---

# 12. Permisos

## User Story

Como propietario,
quiero que Administradores y Vendedores puedan gestionar clientes,
para permitir operaciones rápidas durante los Lives.

## Acceptance Criteria

1. WHEN un Administrador acceda a clientes THEN SHALL poder:

   - Listar.
   - Buscar.
   - Ver.
   - Crear.
   - Editar.

2. WHEN un Vendedor acceda a clientes THEN SHALL poder:

   - Listar.
   - Buscar.
   - Ver.
   - Crear.
   - Editar.

3. WHEN un usuario no autenticado intente acceder THEN SHALL ser redirigido al login.

4. WHEN un usuario inactivo intente acceder THEN SHALL aplicarse el middleware active existente.

5. THE módulo SHALL NOT proporcionar rutas públicas para clientes.

---

# 13. Integridad de datos

## User Story

Como sistema,
quiero mantener clientes consistentes,
para que futuras reservas y pedidos puedan confiar en sus datos.

## Acceptance Criteria

1. THE nombre SHALL ser obligatorio.

2. THE usuario TikTok SHALL ser nullable.

3. THE WhatsApp SHALL ser nullable.

4. WHEN TikTok exista THEN SHALL almacenarse normalizado.

5. WHEN WhatsApp exista THEN SHALL almacenarse normalizado.

6. THE usuario TikTok normalizado SHALL ser único cuando no sea null.

7. THE WhatsApp normalizado SHALL ser único cuando no sea null.

8. THE aplicación SHALL validar integridad tanto mediante solicitudes HTTP como mediante restricciones apropiadas de base de datos.

---

# 14. Preparación para futuras reservas

## User Story

Como desarrollador,
quiero que los clientes estén preparados para integrarse con reservas,
para implementar posteriormente el flujo de un Live.

## Acceptance Criteria

1. THE cliente SHALL disponer de un identificador interno estable.

2. WHEN posteriormente se cree una reserva THEN SHALL poder asociarse mediante customer_id o su equivalente definido en diseño.

3. THE módulo SHALL permitir búsqueda rápida reutilizable desde Modo Live.

4. THE módulo SHALL soportar registro rápido reutilizable desde Modo Live.

5. Esta Spec SHALL NOT implementar todavía:

   - Lives.
   - Reservas.
   - Pedidos.
   - Pagos.
   - Entregas.

---

# 15. Fuera de alcance

Esta Spec no incluirá:

- Login para compradores.
- Portal público del cliente.
- Integración automática con TikTok.
- Lectura automática de comentarios.
- Integración automática con WhatsApp.
- Envío de mensajes.
- Reservas.
- Pedidos.
- Pagos.
- Entregas.
- Estadísticas de clientes.
- Programa de puntos.
- Sistema de fidelización avanzado.
