---

inclusion: always

Flujo de negocio de LiveSales

Objetivo

Este documento describe el flujo principal de una venta realizada mediante una transmisión en vivo.

Servirá como referencia para diseñar:

* Especificaciones.
* Base de datos.
* Interfaces.
* Reglas de negocio.
* Funcionalidades.

---

# 1. Preparación previa al Live

Antes de comenzar una transmisión, el administrador o vendedor registra o selecciona los productos que serán mostrados.

Cada producto debe contar como mínimo con:

* Código único.
* Nombre.
* Descripción.
* Categoría.
* Precio base.
* Estado de conservación.
* Descripción de detalles cuando corresponda.
* Estado operativo.
* Una o más imágenes.

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

---

# 2. Inicio del Live

Cuando comienza la transmisión:

PROGRAMADO → ACTIVO.

El sistema habilita el Modo Live.

Debe mostrar prioritariamente:

* Buscador por código.
* Producto seleccionado.
* Fotografía.
* Nombre.
* Talla.
* Estado de conservación.
* Detalles.
* Precio base.
* Precio Live.
* Disponibilidad.
* Botón Reservar.
* Registro rápido de cliente.
* Últimas reservas.
* Pedidos generados.
* Total vendido durante el Live.

---

# 3. Presentación del producto

El vendedor muestra el producto.

Ejemplo:

"Este es el R025, vestido rojo de 6 a 9 meses, está a Bs 20."

Un cliente escribe que lo desea.

Ejemplo:

"Mío 20."

El vendedor busca:

R025.

LiveSales muestra:

Código: R025
Producto: Vestido rojo
Talla: 6-9 meses
Estado: COMO_NUEVO
Precio base: Bs 20
Precio Live: Bs 20
Disponibilidad: DISPONIBLE

---

# 4. Negociación del precio

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

---

# 5. Identificación del cliente

El vendedor deberá identificar al comprador.

Puede:

1. Buscar un cliente existente.
2. Crear un cliente rápido.

La búsqueda se realizará mediante:

* Nombre.
* TikTok.
* WhatsApp.

Ejemplo de cliente rápido:

Nombre: María
TikTok: @marialpz

WhatsApp podrá completarse posteriormente.

---

# 6. Reserva

Antes de reservar, el servidor comprobará:

* Live ACTIVO.
* Producto DISPONIBLE.
* Producto asociado al Live.
* Ausencia de otra reserva válida.

Si todo es correcto:

* Reserva → ACTIVA.
* Producto → RESERVADO.
* Se registra el cliente.
* Se registra el vendedor.
* Se registra fecha y hora.
* Se registra precio acordado.

Ejemplo:

Producto: R025
Cliente: María
Precio acordado: Bs 13
Reserva: ACTIVA

La reserva ACTIVA todavía representa una intención de compra pendiente de confirmación definitiva.

---

# 7. Intento simultáneo

Dos vendedores pueden intentar reservar el mismo producto.

Solo uno podrá completar la operación.

El segundo recibirá:

"No se puede realizar la reserva. El producto ya se encuentra reservado."

La validación deberá realizarse en el servidor.

La comprobación y la creación deberán protegerse contra condiciones de carrera.

---

# 8. Creación del pedido

Cuando María realiza su primera reserva durante ese Live, el sistema crea o identifica su pedido PENDIENTE.

Ejemplo:

Pedido #P0012

Cliente: María
Live: #15
Estado: PENDIENTE

R025 — Vestido rojo — Bs 13

Subtotal productos:

Bs 13.

---

# 9. Nuevas compras

Si María compra otros productos dentro del mismo Live, se agregan al mismo pedido.

Ejemplo:

R025 — Vestido rojo — Bs 13
R031 — Body — Bs 10
R047 — Pantalón — Bs 15
R052 — Chompa — Bs 20

Subtotal:

Bs 58.

Las prendas permanecen RESERVADAS mientras el pedido continúe pendiente de confirmación.

---

# 10. Contacto mediante WhatsApp

Después o durante el Live, el cliente puede contactar al negocio mediante WhatsApp.

Habitualmente envía una captura de pantalla del producto o productos reservados.

LiveSales no estará integrado directamente con WhatsApp en el MVP.

El vendedor utilizará:

* Captura.
* Usuario de TikTok.
* Nombre.
* WhatsApp.

para identificar al cliente y localizar su pedido.

En este momento podrá completar datos como:

* Nombre.
* WhatsApp.
* Información necesaria para la entrega.

---

# 11. Explicación de condiciones

Una vez identificado el pedido, el vendedor explica al cliente:

* Productos reservados.
* Precios acordados.
* Total provisional.
* Lugares de entrega.
* Modalidad ENTREGA_ACORDADA.
* Funcionamiento de PAQUETERIA.
* Formas de pago.
* Condiciones necesarias para continuar.

Después de proporcionar esta información, el vendedor solicita al cliente una confirmación clara de que desea continuar con la compra.

---

# 12. Inicio del plazo de confirmación

Cuando el vendedor termina de explicar las condiciones y solicita la confirmación, registra esta acción en LiveSales.

El sistema almacenará conceptualmente:

confirmation_requested_at.

Y calculará inicialmente:

confirmation_deadline_at =
confirmation_requested_at + 48 horas.

El plazo no comienza necesariamente al momento de la reserva durante el Live.

Comienza cuando el vendedor ha proporcionado la información necesaria al cliente y solicita una confirmación de compra.

Ejemplo:

Solicitud enviada:

24/09/2026 18:00.

Fecha límite:

26/09/2026 18:00.

Pedido:

PENDIENTE.

Reservas:

ACTIVA.

---

# 13. Cliente pendiente de respuesta

Mientras el cliente no confirme:

Pedido:

PENDIENTE.

Reservas:

ACTIVA.

Productos:

RESERVADO.

El sistema podrá mostrar:

* Fecha de solicitud.
* Fecha límite.
* Tiempo restante.
* Cantidad de recordatorios enviados.
* Último recordatorio.
* Si el plazo está vigente o vencido.

Ejemplo:

Pedido: P0012
Cliente: María
Estado: PENDIENTE
Vence: 26/09/2026 18:00
Recordatorios: 1 de 2

---

# 14. Recordatorios

Durante el periodo de confirmación podrán registrarse hasta dos recordatorios manuales.

Ejemplo operativo:

Primer día:

Recordatorio 1.

Segundo día:

Recordatorio 2.

Los mensajes serán enviados manualmente por el vendedor mediante WhatsApp.

LiveSales no enviará automáticamente mensajes durante el MVP.

Después de enviar un recordatorio, el vendedor podrá registrarlo en LiveSales.

El sistema podrá conservar:

* reminder_count.
* last_reminder_at.

---

# 15. Confirmación del cliente

Si el cliente responde afirmativamente y confirma que desea continuar con la compra:

Pedido:

PENDIENTE → CONFIRMADO.

Reservas:

ACTIVA → CONFIRMADA.

Productos:

permanecen RESERVADOS.

El plazo de confirmación deja de tener efecto.

Confirmar el pedido no significa que las prendas estén VENDIDAS.

Tampoco significa obligatoriamente que exista un pago.

---

# 16. Confirmación sin pago anticipado

Para ENTREGA_ACORDADA será válido que el cliente confirme la compra sin realizar ningún pago anticipado.

Ejemplo:

Cliente:

"Sí quiero todo. Pago cuando nos encontremos."

Resultado:

Pedido:

CONFIRMADO.

Pago:

PENDIENTE.

Total:

Bs 60.

Pagado:

Bs 0.

Saldo:

Bs 60.

Este estado será válido.

---

# 17. Pago total mediante QR

El negocio puede proporcionar externamente su QR mediante WhatsApp.

LiveSales no genera ni verifica automáticamente el pago bancario.

Si el cliente paga todo mediante QR:

Total:

Bs 60.

Pago QR:

Bs 60.

Saldo:

Bs 0.

Estado de pago:

PAGADO.

El vendedor registra manualmente el pago después de comprobarlo.

---

# 18. Pago parcial

Para ENTREGA_ACORDADA el cliente podrá realizar un pago parcial mediante QR.

Ejemplo:

Total:

Bs 60.

Pago QR:

Bs 20.

Saldo:

Bs 40.

Estado:

PARCIAL.

El saldo podrá completarse antes o durante la entrega mediante:

* QR.
* EFECTIVO.

---

# 19. Pago completamente presencial

Para ENTREGA_ACORDADA también podrá acordarse que el cliente no realice ningún pago anticipado.

Ejemplo:

Total:

Bs 60.

Pagado antes de la entrega:

Bs 0.

Saldo:

Bs 60.

Estado:

PENDIENTE.

El cliente podrá pagar completamente durante la entrega.

Antes de marcar la entrega como COMPLETADA deberá registrarse el pago correspondiente.

---

# 20. Registro de pago

Cada vez que exista un pago, el vendedor registra:

* Pedido.
* Método.
* Monto.
* Fecha y hora.
* Usuario que registra el pago.
* Observación opcional.
* Comprobante opcional.

Métodos iniciales:

* QR.
* EFECTIVO.

Un pedido podrá tener varios pagos.

---

# 21. Selección de modalidad de entrega

El vendedor selecciona:

* ENTREGA_ACORDADA.
* PAQUETERIA.

La modalidad afectará las reglas de pago.

---

# 22. Entrega acordada

Para ENTREGA_ACORDADA se registran:

* Fecha.
* Hora.
* Lugar.
* Observaciones.

Antes de la entrega el estado de pago podrá ser:

* PENDIENTE.
* PARCIAL.
* PAGADO.

Si existe saldo pendiente, podrá completarse antes o durante la entrega.

---

# 23. Paquetería

Se registran:

* Nombre de la paquetería.
* Sucursal o lugar.
* Fecha.
* Hora aproximada.
* Observaciones.

Para PAQUETERIA el cliente deberá pagar completamente mediante QR antes de que las prendas sean entregadas a la paquetería.

Antes del envío:

Estado pago = PAGADO.

Saldo = Bs 0.

No se permitirá:

* Pago presencial posterior.
* Saldo pendiente.
* Completar el pago después de entregar el paquete.

---

# 24. Costo de paquetería

Costo inicial:

Bs 2.

El sistema determina si corresponde beneficio de primera compra.

## Primera compra

Productos:

Bs 50.

Costo real de paquetería:

Bs 2.

Negocio asume:

Bs 2.

Cliente debe pagar:

Bs 50.

## Cliente recurrente

Productos:

Bs 50.

Paquetería:

Bs 2.

Cliente debe pagar:

Bs 52.

El sistema conservará por separado:

* Costo real.
* Monto cobrado.
* Monto asumido por negocio.

---

# 25. Estado del pago

El estado se calculará automáticamente.

## PENDIENTE

Total pagado:

Bs 0.

## PARCIAL

Total pagado mayor a Bs 0 pero menor al total que debe pagar el cliente.

## PAGADO

Total pagado igual al total que debe pagar el cliente.

El vendedor no deberá cambiar este estado manualmente.

---

# 26. Programación de entrega

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

---

# 27. Preparación del pedido

Antes de realizar la entrega:

CONFIRMADO → LISTO_PARA_ENTREGA.

Este paso será obligatorio.

Para ENTREGA_ACORDADA podrá existir:

* Pago PENDIENTE.
* Pago PARCIAL.
* Pago PAGADO.

Para PAQUETERIA será obligatorio:

Pago = PAGADO.

Saldo = Bs 0.

Pago realizado mediante QR.

---

# 28. Pago del saldo durante entrega acordada

Si existe saldo pendiente:

Pedido:

LISTO_PARA_ENTREGA.

Ejemplo:

Total:

Bs 60.

Pagado:

Bs 20.

Saldo:

Bs 40.

Durante la entrega el vendedor puede registrar:

Método:

EFECTIVO.

Monto:

Bs 40.

Resultado:

Saldo:

Bs 0.

Estado pago:

PAGADO.

---

# 29. Pago completo durante entrega acordada

También puede ocurrir:

Total:

Bs 60.

Pagado previamente:

Bs 0.

Durante la entrega:

EFECTIVO — Bs 60.

Resultado:

Saldo:

Bs 0.

Estado:

PAGADO.

Solo después podrá completarse la entrega.

---

# 30. Finalización de una entrega acordada

Para completar una ENTREGA_ACORDADA deberán cumplirse:

* Pedido LISTO_PARA_ENTREGA.
* Entrega PROGRAMADA.
* Saldo Bs 0.

Después:

Entrega:

PROGRAMADA → COMPLETADA.

Pedido:

LISTO_PARA_ENTREGA → ENTREGADO.

Productos:

RESERVADO → VENDIDO.

---

# 31. Finalización de paquetería

Para entregar las prendas a PAQUETERIA deberán cumplirse:

* Pedido LISTO_PARA_ENTREGA.
* Pago PAGADO.
* Saldo Bs 0.
* Pago completo mediante QR.

Después de dejar correctamente el pedido en la paquetería:

Entrega:

PROGRAMADA → COMPLETADA.

Pedido:

LISTO_PARA_ENTREGA → ENTREGADO.

Productos:

RESERVADO → VENDIDO.

---

# 32. Cliente sin respuesta

Si el cliente no responde durante el plazo:

confirmation_deadline_at < fecha y hora actual.

y:

Pedido = PENDIENTE.

El sistema mostrará:

PLAZO DE CONFIRMACIÓN VENCIDO.

El vencimiento del reloj por sí solo no modificará automáticamente los registros durante el MVP.

---

# 33. Liberación por falta de respuesta

El vendedor podrá ejecutar:

Liberar por falta de respuesta.

Esta operación deberá aplicarse de forma consistente.

Entonces:

Reservas ACTIVA → EXPIRADA.

Productos RESERVADO → DISPONIBLE.

Si el pedido queda sin reservas válidas:

Pedido → CANCELADO.

Motivo:

SIN_RESPUESTA.

Las reservas CONFIRMADA no deberán expirar mediante este flujo.

---

# 34. Extensión del plazo

Si el cliente responde pero necesita más tiempo:

Ejemplo:

"Sí quiero las prendas, pero dame hasta mañana."

El vendedor podrá establecer una nueva:

confirmation_deadline_at.

Ejemplo:

26/09/2026 18:00
→
27/09/2026 18:00.

Podrá añadirse una observación.

La extensión no confirma automáticamente el pedido.

El pedido permanecerá:

PENDIENTE.

Las reservas permanecerán:

ACTIVA.

---

# 35. Cancelación de una reserva

Si el cliente cancela explícitamente una prenda:

Reserva:

ACTIVA o CONFIRMADA → CANCELADA.

Producto:

RESERVADO → DISPONIBLE.

El pedido recalcula su subtotal.

CANCELADA será diferente de EXPIRADA.

---

# 36. Pedido sin productos válidos

Si se cancela o expira la última reserva válida:

Pedido → CANCELADO.

El motivo dependerá de la causa.

Ejemplos:

* CANCELACION_CLIENTE.
* SIN_RESPUESTA.

---

# 37. Cancelación completa

Puede cancelarse un pedido:

* PENDIENTE.
* CONFIRMADO.
* LISTO_PARA_ENTREGA.

Siempre que la entrega todavía no esté COMPLETADA.

Se cancelan:

* Reservas válidas.
* Entrega programada.

Los productos correspondientes vuelven a:

DISPONIBLE.

---

# 38. Cancelación después de existir pagos

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

---

# 39. Finalización del Live

Al terminar:

ACTIVO → FINALIZADO.

Esto no cancela automáticamente:

* Reservas.
* Pedidos.
* Seguimientos de confirmación.
* Pagos.
* Entregas.

No podrán crearse nuevas reservas.

---

# 40. Cancelación del Live

Transiciones:

PROGRAMADO → CANCELADO.

ACTIVO → CANCELADO.

No se eliminan automáticamente operaciones ya registradas.

No se crean nuevas reservas después de cancelar.

---

# 41. Producto no vendido

Un producto que no se vende:

* Sigue DISPONIBLE.
* Puede utilizarse en otro Live.

Su precio Live anterior no determina obligatoriamente el precio del siguiente Live.

---

# 42. Flujo resumido

Producto DISPONIBLE
→ Live ACTIVO
→ Negociación
→ Cliente identificado
→ Reserva ACTIVA
→ Producto RESERVADO
→ Pedido PENDIENTE
→ Cliente contacta por WhatsApp
→ Condiciones explicadas
→ Solicitud de confirmación
→ Plazo de 48 horas
→ Recordatorios si corresponde

## Si confirma

Reserva ACTIVA
→ CONFIRMADA

Pedido PENDIENTE
→ CONFIRMADO

Luego:

→ Modalidad de entrega
→ Pago PENDIENTE, PARCIAL o PAGADO según corresponda
→ Entrega PROGRAMADA
→ LISTO_PARA_ENTREGA
→ Saldo completado
→ Entrega COMPLETADA
→ Pedido ENTREGADO
→ Producto VENDIDO

## Si no responde

Plazo vencido
→ Liberar por falta de respuesta
→ Reserva EXPIRADA
→ Producto DISPONIBLE
→ Pedido CANCELADO por SIN_RESPUESTA si no quedan reservas válidas

---

# Principios generales

LiveSales deberá:

1. Evitar ventas duplicadas.
2. Mantener precios históricos.
3. Registrar quién realizó cada operación importante.
4. Evitar que reservas sin respuesta bloqueen productos indefinidamente.
5. Diferenciar cancelación de expiración.
6. Permitir seguimiento manual de confirmación.
7. Mantener historial de pagos.
8. Calcular saldos automáticamente.
9. Permitir confirmación sin pago previo para ENTREGA_ACORDADA.
10. Exigir pago completo mediante QR para PAQUETERIA.
11. Evitar cálculos manuales innecesarios.
12. Controlar cupos diarios.
13. Mantener consistencia entre producto, reserva, pedido, pago y entrega.
14. Validar reglas críticas en el servidor.
