---
inclusion: always
---
Flujo de negocio de LiveSales
Objetivo

Este documento describe el flujo principal de una venta realizada mediante una transmisión en vivo.

Servirá como referencia para diseñar:

Especificaciones.
Base de datos.
Interfaces.
Reglas de negocio.
Funcionalidades.
1. Preparación previa al Live

Antes de comenzar una transmisión, el administrador o vendedor registra o selecciona los productos que serán mostrados.

Cada producto debe contar como mínimo con:

Código único.
Nombre.
Descripción.
Categoría.
Precio base.
Estado de conservación.
Descripción de detalles cuando corresponda.
Estado operativo.
Una o más imágenes.

Cada producto representa una unidad física.

Ejemplo:

Código: R025
Nombre: Vestido rojo
Categoría: Ropa infantil
Talla: 6-9 meses
Estado de conservación: COMO_NUEVO
Precio base: Bs 20
Estado: DISPONIBLE

El administrador o vendedor crea una sesión Live.

Ejemplo:

Live #15
Fecha: 18/09/2026
Estado: PROGRAMADO

Posteriormente selecciona los productos asociados.

Solo podrán asociarse productos DISPONIBLES.

2. Inicio del Live

Cuando comienza la transmisión:

PROGRAMADO → ACTIVO

El sistema habilita el Modo Live.

Debe mostrar prioritariamente:

Buscador por código.
Producto seleccionado.
Fotografía.
Nombre.
Talla.
Estado de conservación.
Detalles.
Precio base.
Precio Live.
Disponibilidad.
Botón Reservar.
Registro rápido de cliente.
Últimas reservas.
Pedidos generados.
Total vendido durante el Live.
3. Presentación del producto

El vendedor muestra el producto.

Ejemplo:

"Este es el R025, vestido rojo de 6 a 9 meses, está a Bs 20."

Un cliente escribe que lo desea.

El vendedor busca:

R025

LiveSales muestra:

Código: R025
Producto: Vestido rojo
Talla: 6-9 meses
Estado: COMO_NUEVO
Precio base: Bs 20
Precio Live: Bs 20
Disponibilidad: DISPONIBLE

4. Negociación del precio

Un cliente puede preguntar:

"¿Último?"

El vendedor puede decidir:

"Último Bs 15."

El sistema permite cambiar:

Precio Live:

Bs 20 → Bs 15.

Este cambio no modifica el precio base.

Puede existir posteriormente otra negociación.

Ejemplo:

Cliente:

"¿Bs 13?"

Vendedor:

"Sí."

Al reservar:

Precio acordado:

Bs 13.

Por lo tanto:

Precio base: Bs 20
Precio Live: Bs 15
Precio acordado: Bs 13

5. Identificación del cliente

El vendedor deberá identificar al comprador.

Puede:

Buscar un cliente existente.
Crear un cliente rápido.

La búsqueda se realizará mediante:

Nombre.
TikTok.
WhatsApp.

Ejemplo de cliente rápido:

Nombre: María
TikTok: @marialpz

WhatsApp podrá completarse posteriormente.

6. Reserva

Antes de reservar, el servidor comprobará:

Live ACTIVO.
Producto DISPONIBLE.
Producto asociado al Live.
Ausencia de otra reserva válida.

Si todo es correcto:

Reserva → ACTIVA.
Producto → RESERVADO.
Se registra el cliente.
Se registra el vendedor.
Se registra fecha y hora.
Se registra precio acordado.

Ejemplo:

Producto: R025
Cliente: María
Precio acordado: Bs 13
Reserva: ACTIVA

7. Intento simultáneo

Dos vendedores pueden intentar reservar el mismo producto.

Solo uno podrá completar la operación.

El segundo recibirá:

"No se puede realizar la reserva. El producto ya se encuentra reservado."

La validación deberá realizarse en el servidor.

8. Creación del pedido

Cuando María realiza su primera reserva durante ese Live, el sistema crea o identifica su pedido PENDIENTE.

Ejemplo:

Pedido #P0012

Cliente: María
Live: #15
Estado: PENDIENTE

R025 — Vestido rojo — Bs 13

Subtotal productos:

Bs 13.

9. Nuevas compras

Si María compra otros productos dentro del mismo Live, se agregan al mismo pedido.

Ejemplo:

R025 — Vestido rojo — Bs 13
R031 — Body — Bs 10
R047 — Pantalón — Bs 15
R052 — Chompa — Bs 20

Subtotal:

Bs 58.

10. Contacto posterior mediante WhatsApp

Después o durante el Live, el cliente puede contactar al negocio mediante WhatsApp.

Habitualmente puede enviar una captura de pantalla del producto que reservó.

LiveSales no estará integrado directamente con WhatsApp en el MVP.

El vendedor utilizará la captura o el usuario de TikTok para identificar al cliente y localizar su pedido.

En este momento podrá completar datos como:

Nombre.
WhatsApp.
Información necesaria para la entrega.
11. Revisión y confirmación del pedido

El vendedor revisa con el cliente:

Productos.
Precios acordados.
Subtotal.
Modalidad de entrega.

Cuando el cliente confirma su intención de compra:

Pedido:

PENDIENTE → CONFIRMADO

Reservas:

ACTIVA → CONFIRMADA

Productos:

permanecen RESERVADOS.

Todavía no pasan a VENDIDO.

12. Envío del QR

El negocio proporciona externamente su QR al cliente mediante WhatsApp.

LiveSales no genera ni verifica automáticamente el pago bancario.

Después de verificar que se realizó un pago, el vendedor lo registra manualmente.

13. Registro de pago

El vendedor registra:

Pedido.
Método.
Monto.
Fecha y hora.
Observación opcional.
Comprobante opcional.

Métodos iniciales:

QR.
EFECTIVO.

Ejemplo:

Pedido: P0012
Método: QR
Monto: Bs 20

14. Pago parcial

Supongamos:

Total que debe pagar el cliente:

Bs 60.

Primer pago:

QR — Bs 20.

El sistema calcula:

Total: Bs 60
Pagado: Bs 20
Saldo: Bs 40

Estado de pago:

PARCIAL.

Posteriormente pueden registrarse nuevos pagos.

Ejemplo:

QR — Bs 20
EFECTIVO — Bs 40

Resultado:

Total pagado:

Bs 60.

Estado:

PAGADO.

15. Pago total

Si el cliente realiza un solo pago por todo el pedido:

Total:

Bs 60.

Pago QR:

Bs 60.

Saldo:

Bs 0.

Estado:

PAGADO.

16. Selección de modalidad de entrega

El vendedor selecciona:

ENTREGA_ACORDADA
PAQUETERIA
17. Entrega acordada

Para ENTREGA_ACORDADA se registran:

Fecha.
Hora.
Lugar.
Observaciones.

El cliente puede haber realizado:

Pago total.
Pago parcial.

Si existe saldo pendiente, podrá completarse antes o durante la entrega.

Ejemplo:

Total:

Bs 60.

Pagado por QR:

Bs 20.

Saldo para la entrega:

Bs 40.

18. Paquetería

Se registran:

Nombre de la paquetería.
Sucursal o lugar.
Fecha.
Hora aproximada.
Observaciones.

Para utilizar PAQUETERIA el pedido deberá estar completamente pagado antes de realizar el envío.

No podrá completarse una entrega de PAQUETERIA si:

Saldo > Bs 0.

19. Costo de paquetería

Costo inicial:

Bs 2.

El sistema determina si corresponde beneficio de primera compra.

Primera compra

Productos:

Bs 50.

Costo real de paquetería:

Bs 2.

Negocio asume:

Bs 2.

Cliente debe pagar:

Bs 50.

Cliente recurrente

Productos:

Bs 50.

Paquetería:

Bs 2.

Cliente debe pagar:

Bs 52.

El sistema conservará por separado:

Costo real.
Monto cobrado.
Monto asumido por negocio.
20. Estado del pago

El estado se calculará automáticamente.

PENDIENTE

Total pagado:

Bs 0.

PARCIAL

Total pagado mayor a Bs 0 pero menor al total que debe pagar el cliente.

PAGADO

Total pagado igual al total que debe pagar el cliente.

El vendedor no deberá cambiar este estado manualmente.

21. Programación de entrega

Antes de registrar una entrega, LiveSales comprobará los cupos disponibles.

Máximo inicial:

2 por día.

Tanto ENTREGA_ACORDADA como PAQUETERIA utilizan un cupo.

Ejemplo:

15:00 — Carla — ENTREGA_ACORDADA
17:00 — Daniela — PAQUETERIA

Resultado:

2 / 2.

No puede registrarse una tercera entrega válida.

22. Preparación del pedido

Antes de realizar la entrega:

CONFIRMADO → LISTO_PARA_ENTREGA.

Este paso será obligatorio.

Para PAQUETERIA será obligatorio además:

Estado de pago = PAGADO.

23. Pago del saldo durante entrega acordada

Si existe saldo:

Pedido:

LISTO_PARA_ENTREGA.

Estado pago:

PARCIAL.

Durante la entrega el vendedor registra el pago restante.

Ejemplo:

Saldo:

Bs 40.

Método:

EFECTIVO.

Monto:

Bs 40.

Resultado:

Estado pago → PAGADO.

24. Finalización de una entrega acordada

Para completar una ENTREGA_ACORDADA deberán cumplirse:

Pedido LISTO_PARA_ENTREGA.
Entrega PROGRAMADA.
Saldo Bs 0.

Después:

Entrega:

PROGRAMADA → COMPLETADA.

Pedido:

LISTO_PARA_ENTREGA → ENTREGADO.

Productos:

RESERVADO → VENDIDO.

25. Finalización de paquetería

Para completar PAQUETERIA deberán cumplirse:

Pedido LISTO_PARA_ENTREGA.
Pago PAGADO.
Saldo Bs 0.

Después de dejar correctamente el pedido en la paquetería:

Entrega:

PROGRAMADA → COMPLETADA.

Pedido:

LISTO_PARA_ENTREGA → ENTREGADO.

Productos:

RESERVADO → VENDIDO.

26. Cancelación de una reserva

Si el cliente cancela una prenda:

Reserva:

ACTIVA o CONFIRMADA → CANCELADA.

Producto:

RESERVADO → DISPONIBLE.

El pedido recalcula su subtotal.

27. Pedido sin productos

Si se cancela la última prenda:

Pedido → CANCELADO.

28. Cancelación completa

Puede cancelarse un pedido:

PENDIENTE.
CONFIRMADO.
LISTO_PARA_ENTREGA.

Siempre que la entrega todavía no esté COMPLETADA.

Se cancelan:

Reservas.
Entrega programada.

Los productos vuelven a:

DISPONIBLE.

29. Cancelación después de existir pagos

Los pagos registrados no deberán borrarse.

Ejemplo:

Pedido total:

Bs 60.

Pagado:

Bs 20.

Pedido:

CANCELADO.

LiveSales deberá conservar:

Monto pagado:

Bs 20.

Y señalar que existe un monto que requiere devolución o resolución manual.

La gestión completa de devoluciones queda fuera del MVP.

30. Finalización del Live

Al terminar:

ACTIVO → FINALIZADO.

Esto no cancela automáticamente:

Reservas.
Pedidos.
Pagos.
Entregas.

No podrán crearse nuevas reservas.

31. Cancelación del Live

Transiciones:

PROGRAMADO → CANCELADO

ACTIVO → CANCELADO

No se eliminan automáticamente operaciones ya registradas.

No se crean nuevas reservas después de cancelar.

32. Producto no vendido

Un producto que no se vende:

Sigue DISPONIBLE.
Puede utilizarse en otro Live.

Su precio Live anterior no determina obligatoriamente el precio del siguiente Live.

33. Flujo resumido

Producto DISPONIBLE
→ Live ACTIVO
→ Negociación
→ Cliente identificado
→ Reserva ACTIVA
→ Producto RESERVADO
→ Pedido PENDIENTE
→ Cliente contacta por WhatsApp
→ Pedido CONFIRMADO
→ QR enviado externamente
→ Pago registrado
→ Pago PARCIAL o PAGADO
→ Modalidad de entrega
→ Entrega PROGRAMADA
→ LISTO_PARA_ENTREGA
→ Saldo completado si corresponde
→ Entrega COMPLETADA
→ Pedido ENTREGADO
→ Producto VENDIDO

Principios generales

LiveSales deberá:

Evitar ventas duplicadas.
Mantener precios históricos.
Registrar quién realizó cada operación importante.
Mantener historial de pagos.
Calcular saldos automáticamente.
Impedir paquetería con saldo pendiente.
Evitar cálculos manuales innecesarios.
Controlar cupos diarios.
Mantener consistencia entre producto, reserva, pedido, pago y entrega.
Validar reglas críticas en el servidor.