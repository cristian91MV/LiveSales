---

 inclusion: always

 Reglas de negocio de LiveSales

 Propósito

Este documento contiene las reglas definitivas del MVP.

Cuando exista una ambigüedad entre documentos, estas reglas tendrán prioridad para determinar el comportamiento esperado.

---

# 1. Modelo de producto

Cada producto representa una única unidad física.

No existe stock múltiple dentro de un producto durante el MVP.

Estados:

* DISPONIBLE
* RESERVADO
* VENDIDO
* INACTIVO

---

# 2. Momento de venta

Confirmar un pedido no convierte el producto en VENDIDO.

Mientras exista compromiso con el cliente:

Producto → RESERVADO.

Solo después de completar la entrega:

RESERVADO → VENDIDO.

---

# 3. Relación Pedido-Live

Todo pedido del MVP deberá pertenecer obligatoriamente a:

* Un cliente.
* Una sesión Live.

No existirán ventas independientes del Live durante el MVP.

---

# 4. Permisos del Vendedor

El Vendedor podrá:

* Consultar productos.
* Registrar clientes.
* Crear clientes rápidos.
* Crear reservas.
* Cancelar reservas permitidas.
* Crear pedidos.
* Agregar productos.
* Registrar solicitudes de confirmación.
* Registrar recordatorios.
* Extender plazos de confirmación.
* Liberar reservas vencidas.
* Confirmar pedidos.
* Registrar pagos.
* Consultar saldos.
* Marcar pedidos LISTO_PARA_ENTREGA.
* Registrar entregas.
* Completar entregas.
* Crear Lives.
* Iniciar Lives.
* Finalizar Lives.
* Cancelar Lives.
* Asociar productos a Lives.

El Administrador incluirá estas capacidades y funciones administrativas adicionales.

---

# 5. Reservas fuera del Live

Solo podrán crearse nuevas reservas cuando:

Live = ACTIVO.

No podrán crearse para:

* PROGRAMADO.
* FINALIZADO.
* CANCELADO.

---

# 6. Finalización de Live

Finalizar un Live no cancela operaciones existentes.

Después podrá continuarse:

* Gestionando reservas.
* Gestionando confirmaciones.
* Registrando recordatorios.
* Confirmando pedidos.
* Cancelando reservas.
* Registrando pagos.
* Programando entregas.

---

# 7. Estados de reserva

Estados:

* ACTIVA
* CONFIRMADA
* CANCELADA
* EXPIRADA

ACTIVA y CONFIRMADA son reservas válidas.

CANCELADA y EXPIRADA no deberán bloquear el producto.

EXPIRADA forma parte del MVP.

---

# 8. Significado de EXPIRADA

EXPIRADA representa una reserva cuya intención de compra no fue confirmada dentro del plazo establecido.

No debe confundirse con CANCELADA.

CANCELADA representa una cancelación explícita o administrativa.

EXPIRADA representa vencimiento por falta de confirmación.

---

# 9. Cancelación individual

Podrá cancelarse una reserva incluso después de confirmar el pedido si la entrega no está COMPLETADA.

Reserva:

ACTIVA o CONFIRMADA → CANCELADA.

Producto:

RESERVADO → DISPONIBLE.

El pedido recalculará su total.

---

# 10. Expiración individual

Una reserva ACTIVA podrá pasar a EXPIRADA únicamente mediante el flujo de vencimiento por falta de confirmación.

Reserva:

ACTIVA → EXPIRADA.

Producto:

RESERVADO → DISPONIBLE.

Una reserva CONFIRMADA no podrá expirar mediante este flujo.

---

# 11. Pedido sin productos válidos

Un pedido no podrá permanecer activo sin reservas válidas.

Si la última reserva válida se cancela o expira:

Pedido → CANCELADO.

El motivo deberá conservar la causa.

Ejemplos:

* SIN_RESPUESTA.
* CANCELACION_CLIENTE.

---

# 12. Precio histórico

Cada detalle del pedido conservará el precio acordado.

Cambiar posteriormente:

* Precio base.
* Precio Live.
* Precio del producto.

no modificará pedidos históricos.

---

# 13. Total de productos

Subtotal de productos:

suma de precios históricos de los detalles correspondientes a reservas válidas.

Los costos de entrega se calcularán por separado.

---

# 14. Cancelación completa

Podrán cancelarse pedidos:

* PENDIENTE.
* CONFIRMADO.
* LISTO_PARA_ENTREGA.

si la entrega todavía no está COMPLETADA.

Al cancelar:

* Reservas válidas → CANCELADA.
* Productos correspondientes → DISPONIBLE.
* Entrega PROGRAMADA → CANCELADA.

---

# 15. Estados de entrega

Estados:

* PROGRAMADA
* COMPLETADA
* CANCELADA

---

# 16. Límite diario

Máximo inicial:

2 entregas por día.

Cuentan:

* PROGRAMADAS.
* COMPLETADAS.

No cuentan:

* CANCELADAS.

---

# 17. Cancelación de entrega

PROGRAMADA → CANCELADA.

El cupo queda disponible nuevamente.

---

# 18. Pedido cancelado y entrega

Si un pedido con entrega PROGRAMADA se cancela:

Entrega → CANCELADA.

---

# 19. Productos liberados

Un producto liberado mediante:

* Cancelación.
* Expiración.

puede utilizarse posteriormente en otro Live si continúa siendo vendible.

---

# 20. Asociación Producto-Live

Podrán asociarse productos DISPONIBLES.

Un producto podrá participar en distintos Lives mientras siga disponible.

VENDIDO e INACTIVO no podrán asociarse a un nuevo Live.

---

# 21. Concurrencia de reserva

Antes de crear una reserva el servidor deberá validar:

* Live ACTIVO.
* Producto asociado al Live.
* Producto DISPONIBLE.
* Ausencia de otra reserva válida.

La operación deberá ser atómica.

Solo un usuario podrá ganar una reserva simultánea.

---

# 22. Consistencia

Las operaciones críticas que afecten:

* Pedido.
* Detalle.
* Reserva.
* Producto.
* Seguimiento de confirmación.
* Pago.
* Entrega.

deberán completarse completamente o no producir cambios parciales incompatibles.

---

# 23. Concurrencia de entregas

La validación del límite diario deberá protegerse contra solicitudes simultáneas.

Nunca podrá terminarse con:

3 / 2.

---

# 24. Estados de pedido

Estados:

* PENDIENTE
* CONFIRMADO
* LISTO_PARA_ENTREGA
* ENTREGADO
* CANCELADO

Flujo normal:

PENDIENTE
→ CONFIRMADO
→ LISTO_PARA_ENTREGA
→ ENTREGADO

Cancelaciones posibles:

PENDIENTE → CANCELADO

CONFIRMADO → CANCELADO

LISTO_PARA_ENTREGA → CANCELADO

---

# 25. Significado de PENDIENTE

Un pedido PENDIENTE representa un pedido que todavía no fue confirmado definitivamente por el cliente.

Puede contener una o varias reservas ACTIVA.

Puede encontrarse:

* Antes de iniciar el plazo de confirmación.
* Dentro del plazo de confirmación.
* Con plazo vencido pendiente de liberación manual.

---

# 26. Significado de CONFIRMADO

Un pedido pasa a CONFIRMADO cuando el cliente expresa claramente que desea continuar con la compra.

Cuando se confirma:

Reservas ACTIVA → CONFIRMADA.

Los productos permanecen:

RESERVADO.

Confirmar un pedido no significa que el pedido esté pagado.

---

# 27. Finalización de la venta

Una venta se considera completada cuando:

* Saldo = Bs 0.
* Entrega = COMPLETADA.
* Pedido = ENTREGADO.
* Productos = VENDIDO.

---

# 28. Principios de integridad

1. Una unidad física no puede tener dos reservas válidas simultáneamente.
2. Un producto VENDIDO no puede reservarse.
3. Un producto RESERVADO debe tener una reserva ACTIVA o CONFIRMADA.
4. CANCELADA y EXPIRADA no bloquean productos.
5. Un pedido sin reservas válidas no puede permanecer activo.
6. El precio histórico no puede cambiar por modificar el producto.
7. Un Live no ACTIVO no acepta reservas.
8. No se pueden superar los cupos diarios.
9. Una entrega CANCELADA no ocupa cupo.
10. Un pedido ENTREGADO no puede modificarse mediante el flujo ordinario.
11. Un pago registrado no deberá desaparecer del historial.
12. Una reserva vencida no deberá bloquear indefinidamente una unidad física.

---

# 29. Estados del Live

Estados:

* PROGRAMADO
* ACTIVO
* FINALIZADO
* CANCELADO

---

# 30. Transiciones del Live

Permitidas:

PROGRAMADO → ACTIVO

PROGRAMADO → CANCELADO

ACTIVO → FINALIZADO

ACTIVO → CANCELADO

No podrán revertirse FINALIZADO ni CANCELADO mediante el flujo normal.

---

# 31. Gestión del Live

Administrador y Vendedor podrán gestionar Lives.

Productos podrán agregarse cuando:

* PROGRAMADO.
* ACTIVO.

No podrán agregarse cuando:

* FINALIZADO.
* CANCELADO.

---

# 32. LISTO_PARA_ENTREGA obligatorio

No podrá realizarse:

CONFIRMADO → ENTREGADO.

Debe realizarse:

CONFIRMADO
→ LISTO_PARA_ENTREGA
→ ENTREGADO.

---

# 33. Cancelación en LISTO_PARA_ENTREGA

Puede cancelarse si la entrega todavía no está COMPLETADA.

Al cancelar:

* Pedido → CANCELADO.
* Reservas válidas → CANCELADA.
* Productos → DISPONIBLE.
* Entrega → CANCELADA.

---

# 34. Identificación de clientes

Datos:

* Nombre.
* Usuario TikTok.
* WhatsApp.

Durante el Live se permitirá registro rápido.

El WhatsApp podrá completarse posteriormente.

---

# 35. Cliente nuevo y recurrente

Un cliente se considera recurrente cuando tiene al menos un pedido anterior ENTREGADO.

Los CANCELADOS no cuentan.

---

# 36. Modalidades de entrega

Modalidades:

* ENTREGA_ACORDADA
* PAQUETERIA

Ambas ocupan un cupo diario.

---

# 37. Costo de paquetería

Costo inicial:

Bs 2.

Deberán conservarse:

* Costo real.
* Monto cobrado al cliente.
* Monto asumido por negocio.

El valor deberá poder configurarse en el futuro.

---

# 38. Estado de conservación

Valores:

* NUEVO_SIN_USO
* COMO_NUEVO
* BUEN_ESTADO
* CON_DETALLES

Podrá registrarse una descripción.

---

# 39. Precio base

Es una referencia comercial.

No obliga a que el producto se venda por ese monto.

---

# 40. Precio Live

Puede establecerse un precio específico para un producto dentro de una sesión Live.

No modifica el precio base.

---

# 41. Precio acordado

Es el precio finalmente aceptado entre vendedor y cliente.

Se utilizará en el detalle del pedido.

---

# 42. Precio histórico

Una vez incorporado al pedido, el precio de venta no cambiará aunque se modifiquen otros precios.

---

# 43. Negociación

El sistema permitirá vender por debajo:

* Del precio base.
* Del precio Live.

No bloqueará estas negociaciones.

---

# 44. Entidad Pago

Un pedido podrá tener cero, uno o varios pagos asociados.

Cada pago deberá registrar como mínimo:

* Pedido.
* Método.
* Monto.
* Fecha y hora.
* Usuario que lo registró.

Opcionalmente:

* Observación.
* Imagen o comprobante.

---

# 45. Métodos de pago

Métodos iniciales:

* QR
* EFECTIVO

Otros métodos podrán añadirse posteriormente.

LiveSales no procesará automáticamente estos pagos durante el MVP.

---

# 46. Estado del pago

El estado del pago será calculado, no seleccionado manualmente.

## PENDIENTE

Total de pagos válidos:

Bs 0.

## PARCIAL

Total pagado:

mayor a Bs 0 y menor al monto total que debe pagar el cliente.

## PAGADO

Total pagado:

igual al monto total que debe pagar el cliente.

Conceptualmente:

SALDO =
TOTAL_CLIENTE - TOTAL_PAGOS_VALIDOS.

---

# 47. Confirmación y pago son independientes

La confirmación de compra y el pago serán conceptos independientes.

Podrá existir:

Pedido = CONFIRMADO.

Pago = PENDIENTE.

Saldo > Bs 0.

Esto será válido para ENTREGA_ACORDADA.

---

# 48. Formas de pago para ENTREGA_ACORDADA

Para ENTREGA_ACORDADA serán válidos:

1. Pago completo mediante QR antes de la entrega.
2. Pago parcial mediante QR y saldo antes o durante la entrega.
3. Pago completo presencial durante la entrega.

Por lo tanto, para programar o preparar una ENTREGA_ACORDADA el estado de pago podrá ser:

* PENDIENTE.
* PARCIAL.
* PAGADO.

Una ENTREGA_ACORDADA no podrá marcarse COMPLETADA mientras exista:

Saldo > Bs 0.

---

# 49. Reglas de pago para PAQUETERIA

PAQUETERIA exige pago completo mediante QR antes de entregar las prendas a la empresa de paquetería.

Antes del envío deberá cumplirse:

Estado pago = PAGADO.

Saldo = Bs 0.

Además, el monto correspondiente al cliente deberá haberse registrado mediante QR.

No se permitirá:

* Pago presencial posterior.
* Saldo pendiente.
* Pago parcial que deba completarse después del despacho.

---

# 50. Beneficio de primera compra en paquetería

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

Si se asigna a un pedido y ese pedido posteriormente se cancela antes de completarse, el beneficio podrá quedar disponible nuevamente.

---

# 51. Total que debe pagar el cliente

El monto total a pagar será:

SUBTOTAL_PRODUCTOS

* CARGOS_PAGADOS_POR_CLIENTE.

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

---

# 52. Registro de pagos

Los pagos registrados no deberán editarse libremente para ocultar errores.

Cuando sea necesario corregir una operación deberán conservarse mecanismos de trazabilidad.

Los detalles específicos de anulaciones podrán definirse durante la especificación del módulo Pagos.

---

# 53. Pago superior al saldo

Durante el MVP no deberá registrarse normalmente un pago mayor al saldo pendiente.

Ejemplo:

Saldo:

Bs 20.

Intento:

Registrar Bs 25.

Resultado:

La operación deberá rechazarse o requerir una corrección antes de registrarse.

---

# 54. Cancelación con pagos existentes

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

---

# 55. WhatsApp

WhatsApp permanecerá como canal externo durante el MVP.

El sistema podrá utilizar datos obtenidos por WhatsApp, pero no:

* Leerá mensajes automáticamente.
* Enviará mensajes automáticamente.
* Verificará capturas automáticamente.
* Enviará QR automáticamente.
* Enviará recordatorios automáticamente.

Estas integraciones podrán desarrollarse posteriormente.

---

# 56. Comprobante

El comprobante de un pago será opcional dentro del MVP.

Podrá almacenarse como evidencia cuando resulte útil.

La existencia de una imagen no sustituirá la validación manual del vendedor.

---

# 57. Inicio del plazo de confirmación

El plazo de confirmación no comienza automáticamente al momento de reservar un producto durante el Live.

Comenzará cuando el vendedor haya:

1. Identificado al cliente mediante WhatsApp.
2. Explicado las condiciones de entrega.
3. Explicado las formas de pago.
4. Solicitado una confirmación de compra.
5. Registrado esta acción en LiveSales.

El sistema almacenará:

confirmation_requested_at.

El plazo inicial será:

48 horas.

Se calculará:

confirmation_deadline_at =
confirmation_requested_at + 48 horas.

---

# 58. Recordatorios de confirmación

Mientras el pedido permanezca PENDIENTE podrán registrarse hasta dos recordatorios.

Los recordatorios serán enviados manualmente mediante WhatsApp.

LiveSales no enviará mensajes automáticamente durante el MVP.

El sistema podrá registrar:

* reminder_count.
* last_reminder_at.

La práctica inicial del negocio será:

Primer recordatorio:

durante el primer día.

Segundo recordatorio:

durante el segundo día.

El número máximo inicial será:

2.

---

# 59. Pedido vencido

Un pedido se considerará vencido para efectos de confirmación cuando:

* Estado = PENDIENTE.
* confirmation_deadline_at no sea null.
* confirmation_deadline_at sea anterior a la fecha y hora actual.

El vencimiento del plazo no modificará automáticamente los registros durante el MVP.

El sistema deberá mostrar claramente que el plazo venció.

---

# 60. Liberación por falta de respuesta

Cuando un pedido PENDIENTE tenga el plazo vencido, el vendedor podrá ejecutar:

Liberar por falta de respuesta.

Esta operación deberá ser consistente.

Las reservas:

ACTIVA → EXPIRADA.

Los productos:

RESERVADO → DISPONIBLE.

Si después de la operación no quedan reservas válidas:

Pedido → CANCELADO.

Motivo:

SIN_RESPUESTA.

Las reservas CONFIRMADA no podrán expirar mediante esta operación.

---

# 61. Extensión del plazo

Mientras un pedido permanezca PENDIENTE, el vendedor podrá extender manualmente su plazo de confirmación.

La extensión modificará:

confirmation_deadline_at.

Podrá registrarse una observación explicando la razón.

Ejemplo:

26/09/2026 18:00
→
27/09/2026 18:00.

La extensión no cambia automáticamente:

* Estado del pedido.
* Estado de las reservas.
* Estado de pago.

---

# 62. Recordatorio no implica confirmación

Registrar un recordatorio no confirma el pedido.

Después de un recordatorio:

Pedido continúa:

PENDIENTE.

Reservas continúan:

ACTIVA.

Hasta que:

* El cliente confirme.
* El cliente cancele.
* El pedido sea liberado por vencimiento.

---

# 63. Confirmación dentro del plazo

Si el cliente responde afirmativamente:

Pedido:

PENDIENTE → CONFIRMADO.

Reservas:

ACTIVA → CONFIRMADA.

A partir de ese momento:

* El plazo deja de utilizarse.
* Las reservas no podrán EXPIRAR por falta de respuesta.
* El proceso continúa hacia pago y entrega.

---

# 64. Expiración no automática en el MVP

Durante el MVP no será obligatorio utilizar Laravel Scheduler ni procesos en segundo plano para expirar reservas.

El sistema calculará si el plazo está vencido comparando:

confirmation_deadline_at

con:

fecha y hora actual.

La liberación será iniciada manualmente por Administrador o Vendedor.

La automatización podrá incorporarse posteriormente.

---

# 65. Motivo SIN_RESPUESTA

Cuando un pedido sea cancelado debido a la expiración de todas sus reservas por falta de confirmación:

Pedido = CANCELADO.

Motivo = SIN_RESPUESTA.

Este motivo deberá diferenciarse de otras causas de cancelación.

---

# 66. Invariantes financieras

1. El saldo nunca deberá calcularse manualmente.
2. El saldo depende del total del pedido y de los pagos válidos.
3. Confirmar un pedido no obliga a registrar un pago anticipado para ENTREGA_ACORDADA.
4. Un pedido de PAQUETERIA debe estar completamente pagado mediante QR antes del envío.
5. Una entrega no puede completarse con saldo pendiente.
6. Los pagos históricos no desaparecen al cancelar un pedido.
7. Los costos asumidos por el negocio no se cobran al cliente.
8. El precio histórico de una prenda no cambia después de formar parte del pedido.
9. Un pago no deberá superar normalmente el saldo pendiente.

---

# 67. Invariantes del seguimiento de confirmación

1. Solo pedidos PENDIENTE podrán encontrarse esperando confirmación.
2. Una reserva CONFIRMADA no puede pasar a EXPIRADA por este flujo.
3. Una reserva EXPIRADA no bloquea el producto.
4. Un producto liberado por expiración vuelve a DISPONIBLE.
5. Un pedido sin reservas válidas no puede permanecer PENDIENTE.
6. Un recordatorio no confirma una compra.
7. Extender el plazo no confirma una compra.
8. El plazo de confirmación y el estado de pago son conceptos independientes.
9. El vencimiento temporal por sí solo no cambia automáticamente estados durante el MVP.
10. La liberación por falta de respuesta deberá realizarse como una operación consistente.
