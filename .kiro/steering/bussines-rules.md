---
inclusion: always
---
Reglas de negocio de LiveSales
Propósito

Este documento contiene las reglas definitivas del MVP.

Cuando exista una ambigüedad entre documentos, estas reglas tendrán prioridad para determinar el comportamiento esperado.

1. Modelo de producto

Cada producto representa una única unidad física.

No existe stock múltiple dentro de un producto durante el MVP.

Estados:

DISPONIBLE
RESERVADO
VENDIDO
INACTIVO
2. Momento de venta

Confirmar un pedido no convierte el producto en VENDIDO.

Mientras exista compromiso con el cliente:

Producto → RESERVADO.

Solo después de completar la entrega:

RESERVADO → VENDIDO.

3. Relación Pedido-Live

Todo pedido del MVP deberá pertenecer obligatoriamente a:

Un cliente.
Una sesión Live.

No existirán ventas independientes del Live durante el MVP.

4. Permisos del Vendedor

El Vendedor podrá:

Consultar productos.
Registrar clientes.
Crear clientes rápidos.
Crear reservas.
Cancelar reservas permitidas.
Crear pedidos.
Agregar productos.
Confirmar pedidos.
Registrar pagos.
Consultar saldos.
Marcar pedidos LISTO_PARA_ENTREGA.
Registrar entregas.
Completar entregas.
Crear Lives.
Iniciar Lives.
Finalizar Lives.
Cancelar Lives.
Asociar productos a Lives.

El Administrador incluirá estas capacidades y funciones administrativas adicionales.

5. Reservas fuera del Live

Solo podrán crearse nuevas reservas cuando:

Live = ACTIVO.

No podrán crearse para:

PROGRAMADO.
FINALIZADO.
CANCELADO.
6. Finalización de Live

Finalizar un Live no cancela operaciones existentes.

Después podrá continuarse:

Confirmando reservas.
Cancelando reservas.
Gestionando pedidos.
Registrando pagos.
Programando entregas.
7. Estados de reserva

Estados:

ACTIVA
CONFIRMADA
CANCELADA

EXPIRADA queda fuera del MVP.

8. Cancelación individual

Podrá cancelarse una reserva incluso después de confirmar el pedido si la entrega no está COMPLETADA.

Reserva:

CONFIRMADA → CANCELADA.

Producto:

RESERVADO → DISPONIBLE.

El pedido recalculará su total.

9. Pedido sin productos

Un pedido no podrá permanecer activo sin productos.

Al cancelar el último:

Pedido → CANCELADO.

10. Precio histórico

Cada detalle del pedido conservará el precio acordado.

Cambiar posteriormente:

Precio base.
Precio Live.
Precio del producto.

no modificará pedidos históricos.

11. Total de productos

Subtotal de productos:

suma de precios históricos de los detalles válidos.

Los costos de entrega se calcularán por separado.

12. Cancelación completa

Podrán cancelarse pedidos:

PENDIENTE.
CONFIRMADO.
LISTO_PARA_ENTREGA.

si la entrega todavía no está COMPLETADA.

Al cancelar:

Reservas → CANCELADA.
Productos → DISPONIBLE.
Entrega PROGRAMADA → CANCELADA.
13. Estados de entrega

Estados:

PROGRAMADA
COMPLETADA
CANCELADA
14. Límite diario

Máximo inicial:

2 entregas por día.

Cuentan:

PROGRAMADAS.
COMPLETADAS.

No cuentan:

CANCELADAS.
15. Cancelación de entrega

PROGRAMADA → CANCELADA.

El cupo queda disponible nuevamente.

16. Pedido cancelado y entrega

Si un pedido con entrega PROGRAMADA se cancela:

Entrega → CANCELADA.

17. Productos liberados

Un producto liberado puede utilizarse posteriormente en otro Live.

18. Asociación Producto-Live

Podrán asociarse productos DISPONIBLES.

Un producto podrá participar en distintos Lives mientras siga disponible.

VENDIDO e INACTIVO no podrán asociarse a un nuevo Live.

19. Concurrencia de reserva

Antes de crear una reserva el servidor deberá validar:

Live ACTIVO.
Producto asociado al Live.
Producto DISPONIBLE.
Ausencia de otra reserva válida.

La operación deberá ser atómica.

Solo un usuario podrá ganar una reserva simultánea.

20. Consistencia

Las operaciones críticas que afecten:

Pedido.
Detalle.
Reserva.
Producto.
Pago.
Entrega.

deberán completarse completamente o no producir cambios parciales incompatibles.

21. Concurrencia de entregas

La validación del límite diario deberá protegerse contra solicitudes simultáneas.

Nunca podrá terminarse con:

3 / 2.

22. Estados de pedido

Estados:

PENDIENTE
CONFIRMADO
LISTO_PARA_ENTREGA
ENTREGADO
CANCELADO

Flujo normal:

PENDIENTE
→ CONFIRMADO
→ LISTO_PARA_ENTREGA
→ ENTREGADO

Cancelaciones posibles:

PENDIENTE → CANCELADO

CONFIRMADO → CANCELADO

LISTO_PARA_ENTREGA → CANCELADO

23. Finalización de la venta

Una venta se considera completada cuando:

Saldo = Bs 0.
Entrega = COMPLETADA.
Pedido = ENTREGADO.
Productos = VENDIDO.
24. Principios de integridad
Una unidad física no puede tener dos reservas válidas simultáneamente.
Un producto VENDIDO no puede reservarse.
Un producto RESERVADO debe tener una reserva válida.
Un pedido sin productos no puede permanecer activo.
El precio histórico no puede cambiar por modificar el producto.
Un Live no ACTIVO no acepta reservas.
No se pueden superar los cupos diarios.
Una entrega CANCELADA no ocupa cupo.
Un pedido ENTREGADO no puede modificarse mediante el flujo ordinario.
Un pago registrado no deberá desaparecer del historial.
25. Estados del Live

Estados:

PROGRAMADO
ACTIVO
FINALIZADO
CANCELADO
26. Transiciones del Live

Permitidas:

PROGRAMADO → ACTIVO

PROGRAMADO → CANCELADO

ACTIVO → FINALIZADO

ACTIVO → CANCELADO

No podrán revertirse FINALIZADO ni CANCELADO mediante el flujo normal.

27. Gestión del Live

Administrador y Vendedor podrán gestionar Lives.

Productos podrán agregarse cuando:

PROGRAMADO.
ACTIVO.

No podrán agregarse cuando:

FINALIZADO.
CANCELADO.
28. LISTO_PARA_ENTREGA obligatorio

No podrá realizarse:

CONFIRMADO → ENTREGADO.

Debe realizarse:

CONFIRMADO
→ LISTO_PARA_ENTREGA
→ ENTREGADO.

29. Cancelación en LISTO_PARA_ENTREGA

Puede cancelarse si la entrega todavía no está COMPLETADA.

Al cancelar:

Pedido → CANCELADO.
Reservas → CANCELADA.
Productos → DISPONIBLE.
Entrega → CANCELADA.
30. Identificación de clientes

Datos:

Nombre.
Usuario TikTok.
WhatsApp.

Durante el Live se permitirá registro rápido.

El WhatsApp podrá completarse posteriormente.

31. Cliente nuevo y recurrente

Un cliente se considera recurrente cuando tiene al menos un pedido anterior ENTREGADO.

Los CANCELADOS no cuentan.

32. Modalidades de entrega

Modalidades:

ENTREGA_ACORDADA
PAQUETERIA

Ambas ocupan un cupo diario.

33. Costo de paquetería

Costo inicial:

Bs 2.

Deberán conservarse:

Costo real.
Monto cobrado al cliente.
Monto asumido por negocio.

El valor deberá poder configurarse en el futuro.

34. Estado de conservación

Valores:

NUEVO_SIN_USO
COMO_NUEVO
BUEN_ESTADO
CON_DETALLES

Podrá registrarse una descripción.

35. Precio base

Es una referencia comercial.

No obliga a que el producto se venda por ese monto.

36. Precio Live

Puede establecerse un precio específico para un producto dentro de una sesión Live.

No modifica el precio base.

37. Precio acordado

Es el precio finalmente aceptado entre vendedor y cliente.

Se utilizará en el detalle del pedido.

38. Precio histórico

Una vez incorporado al pedido, el precio de venta no cambiará aunque se modifiquen otros precios.

39. Negociación

El sistema permitirá vender por debajo:

Del precio base.
Del precio Live.

No bloqueará estas negociaciones.

40. Entidad Pago

Un pedido podrá tener cero, uno o varios pagos asociados.

Cada pago deberá registrar como mínimo:

Pedido.
Método.
Monto.
Fecha y hora.
Usuario que lo registró.

Opcionalmente:

Observación.
Imagen o comprobante.
41. Métodos de pago

Métodos iniciales:

QR
EFECTIVO

Otros métodos podrán añadirse posteriormente.

LiveSales no procesará automáticamente estos pagos durante el MVP.

42. Estado del pago

El estado del pago será calculado, no seleccionado manualmente.

PENDIENTE

Total de pagos válidos:

Bs 0.

PARCIAL

Total pagado:

mayor a Bs 0 y menor al monto total que debe pagar el cliente.

PAGADO

Total pagado:

igual al monto total que debe pagar el cliente.

Conceptualmente:

SALDO =
TOTAL_CLIENTE - TOTAL_PAGOS_VALIDOS

43. Pagos parciales

Para ENTREGA_ACORDADA se permitirán pagos parciales.

Ejemplo:

Total:

Bs 60.

Pago QR:

Bs 20.

Saldo:

Bs 40.

El saldo podrá completarse posteriormente mediante QR o EFECTIVO.

44. Reglas de pago para entrega acordada

Para programar o continuar una ENTREGA_ACORDADA, el pedido podrá encontrarse:

PARCIAL.
PAGADO.

El saldo restante podrá pagarse antes o durante la entrega.

Una ENTREGA_ACORDADA no podrá marcarse COMPLETADA mientras exista:

Saldo > Bs 0.

45. Reglas de pago para paquetería

La modalidad PAQUETERIA exige pago completo.

Antes de completar el proceso de envío:

Estado pago deberá ser:

PAGADO.

Saldo deberá ser:

Bs 0.

No podrá completarse PAQUETERIA con pago PARCIAL o PENDIENTE.

46. Beneficio de primera compra en paquetería

Si un cliente utiliza PAQUETERIA y todavía no tiene compras ENTREGADAS:

Costo real:

Bs 2.

Cliente paga por paquetería:

Bs 0.

Negocio asume:

Bs 2.

Cuando sea recurrente:

Costo real:

Bs 2.

Cliente paga:

Bs 2.

Negocio asume:

Bs 0.

El beneficio solo podrá utilizarse una vez.

Si se asigna a un pedido y ese pedido posteriormente se cancela, el beneficio podrá quedar disponible nuevamente.

47. Total que debe pagar el cliente

El monto total a pagar será:

SUBTOTAL_PRODUCTOS

CARGOS_PAGADOS_POR_CLIENTE

Los costos asumidos por el negocio no incrementarán el monto que debe pagar el cliente.

Ejemplo primera compra con paquetería:

Productos:

Bs 50.

Costo real paquetería:

Bs 2.

Negocio asume:

Bs 2.

TOTAL_CLIENTE:

Bs 50.

48. Registro de pagos

Los pagos registrados no deberán editarse libremente para ocultar errores.

Cuando sea necesario corregir una operación deberán conservarse mecanismos de trazabilidad.

Los detalles específicos de anulaciones podrán definirse durante la especificación del módulo Pagos.

49. Pago superior al saldo

Durante el MVP no deberá registrarse normalmente un pago mayor al saldo pendiente.

Ejemplo:

Saldo:

Bs 20.

Intento:

Registrar Bs 25.

Resultado:

La operación deberá rechazarse o requerir una corrección antes de registrarse.

50. Cancelación con pagos existentes

Cancelar un pedido no elimina los pagos registrados.

Ejemplo:

Pedido:

Bs 60.

Pagado:

Bs 20.

Pedido:

CANCELADO.

El sistema conservará:

Pagado anteriormente:

Bs 20.

Y deberá mostrar que existe un monto pendiente de devolución o resolución manual.

La gestión completa de devoluciones queda fuera del MVP.

51. WhatsApp

WhatsApp permanecerá como canal externo durante el MVP.

El sistema podrá utilizar datos obtenidos por WhatsApp, pero no:

Leerá mensajes automáticamente.
Enviará mensajes automáticamente.
Verificará capturas automáticamente.
Enviará QR automáticamente.

Estas integraciones podrán desarrollarse posteriormente.

52. Comprobante

El comprobante de un pago será opcional dentro del MVP.

Podrá almacenarse como evidencia cuando resulte útil.

La existencia de una imagen no sustituirá la validación manual del vendedor.

53. Invariantes financieras
El saldo nunca deberá calcularse manualmente.
El saldo depende del total del pedido y de los pagos válidos.
Un pedido de PAQUETERIA no puede completarse con saldo pendiente.
Una entrega no puede completarse con saldo pendiente.
Los pagos históricos no desaparecen al cancelar un pedido.
Los costos asumidos por el negocio no se cobran al cliente.
El precio histórico de una prenda no cambia después de formar parte del pedido.
Un pago no deberá superar normalmente el saldo pendiente.