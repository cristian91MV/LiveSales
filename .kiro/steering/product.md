---

 inclusion: always

 LiveSales

 Descripción del producto

LiveSales es un sistema web orientado a pequeños negocios y emprendimientos que realizan ventas de productos mediante transmisiones en vivo en redes sociales, especialmente TikTok Live.

El sistema busca centralizar y simplificar la gestión de:

* Productos.
* Clientes.
* Sesiones Live.
* Reservas.
* Pedidos.
* Seguimiento de confirmación.
* Pagos.
* Entregas.

LiveSales no pretende sustituir a TikTok ni realizar la transmisión dentro de la plataforma.

Su función principal es servir como herramienta interna de gestión mientras el vendedor realiza un Live y posteriormente coordina la confirmación de la compra, el pago y la entrega con el cliente mediante canales externos como WhatsApp.

La primera versión será utilizada principalmente de forma local desde una computadora o laptop.

---

# Problema

Los vendedores que realizan ventas mediante transmisiones en vivo suelen gestionar sus operaciones utilizando:

* Comentarios del Live.
* Mensajes de WhatsApp.
* Capturas de pantalla.
* Anotaciones manuales.
* Hojas de cálculo.
* Memoria del vendedor.

Cuando aumenta la cantidad de compradores pueden aparecer problemas como:

* Dos personas intentando reservar el mismo producto.
* Dificultad para determinar quién reservó primero.
* Venta accidental de un producto a más de una persona.
* Pérdida de información sobre reservas.
* Dificultad para identificar compradores recurrentes.
* Pedidos de un mismo cliente separados.
* Dificultad para conocer el total que debe pagar un cliente.
* Cambios de precio durante el Live sin registro.
* Dificultad para recordar el precio finalmente acordado.
* Dificultad para saber cuánto pagó un cliente y cuánto todavía debe.
* Confusión entre pagos totales y parciales.
* Clientes que reservan prendas y posteriormente dejan de responder.
* Prendas bloqueadas durante demasiado tiempo por reservas sin confirmar.
* Dificultad para recordar a qué clientes ya se envió un recordatorio.
* Dificultad para saber cuándo corresponde liberar una prenda.
* Desorganización al programar entregas.
* Sobrepasar los cupos diarios de entrega.
* Dificultad para controlar pedidos enviados mediante paquetería.
* Falta de estadísticas sobre ventas y descuentos.

---

# Solución propuesta

LiveSales centralizará el proceso de venta mediante Live.

El flujo general será:

Producto
→ Live
→ Cliente
→ Reserva
→ Pedido
→ Confirmación
→ Pago
→ Entrega
→ Venta completada

Durante una transmisión, el vendedor podrá:

* Buscar rápidamente un producto.
* Consultar su información.
* Verificar disponibilidad.
* Consultar su precio.
* Negociar un nuevo precio.
* Identificar o registrar rápidamente a un cliente.
* Reservar el producto.
* Agrupar varias reservas dentro de un mismo pedido.

Después del Live, el vendedor podrá continuar el proceso mediante WhatsApp.

El cliente podrá enviar una captura de la prenda o prendas que reservó y el vendedor podrá localizar su pedido, explicarle las modalidades de entrega, formas de pago y solicitar una confirmación.

LiveSales permitirá controlar cuánto tiempo lleva esperando una confirmación y registrar recordatorios manuales.

Si el cliente deja de responder y vence el plazo establecido, las prendas podrán liberarse para volver a estar disponibles.

LiveSales no estará integrado automáticamente con WhatsApp durante el MVP.

Tampoco procesará automáticamente pagos mediante QR.

El sistema registrará manualmente los pagos realizados y calculará automáticamente:

* Total del pedido.
* Total pagado.
* Saldo pendiente.
* Estado del pago.

---

# Usuarios objetivo

LiveSales está dirigido principalmente a:

* Pequeños emprendimientos.
* Vendedores independientes.
* Tiendas que venden mediante TikTok Live.
* Negocios de ropa nueva.
* Negocios de ropa de segunda mano.
* Vendedores de accesorios.
* Vendedores de calzado.
* Vendedores de cosméticos.
* Vendedores de juguetes.
* Bazares.
* Otros negocios que utilicen Lives como canal de venta.

El sistema no deberá diseñarse exclusivamente para ropa infantil.

---

# Roles iniciales

## Administrador

Podrá:

* Gestionar usuarios.
* Gestionar roles y permisos.
* Gestionar categorías.
* Gestionar productos.
* Gestionar clientes.
* Crear sesiones Live.
* Iniciar sesiones Live.
* Finalizar sesiones Live.
* Cancelar sesiones Live.
* Asociar productos a Lives.
* Gestionar reservas.
* Gestionar pedidos.
* Registrar solicitudes de confirmación.
* Registrar recordatorios.
* Extender plazos de confirmación.
* Liberar pedidos vencidos.
* Registrar pagos.
* Gestionar entregas.
* Gestionar configuraciones.
* Consultar estadísticas.

## Vendedor

Podrá:

* Consultar productos.
* Registrar clientes.
* Crear clientes rápidos durante un Live.
* Utilizar el Modo Live.
* Crear reservas.
* Cancelar reservas permitidas.
* Agregar productos a pedidos.
* Consultar pedidos.
* Registrar solicitudes de confirmación.
* Registrar recordatorios manuales.
* Extender plazos de confirmación.
* Liberar reservas vencidas.
* Confirmar pedidos.
* Registrar pagos.
* Consultar saldos pendientes.
* Marcar pedidos como LISTO_PARA_ENTREGA.
* Registrar entregas.
* Completar entregas.
* Crear sesiones Live.
* Iniciar sesiones Live.
* Finalizar sesiones Live.
* Cancelar sesiones Live.
* Asociar productos a Lives PROGRAMADOS o ACTIVOS.

---

# Módulos del MVP

La primera versión incluirá:

1. Autenticación.
2. Usuarios y roles.
3. Categorías.
4. Productos.
5. Clientes.
6. Sesiones Live.
7. Modo Live.
8. Reservas.
9. Pedidos.
10. Seguimiento de confirmación.
11. Pagos.
12. Entregas.
13. Dashboard básico.
14. Configuración básica.

---

# Característica diferenciadora

La funcionalidad principal será el Modo Live.

Esta interfaz deberá estar diseñada para realizar operaciones rápidamente mientras se desarrolla una transmisión.

El vendedor deberá poder:

* Buscar un producto por código.
* Consultar sus fotografías.
* Consultar nombre y talla.
* Ver su estado de conservación.
* Consultar posibles detalles o defectos.
* Ver su disponibilidad.
* Consultar su precio base.
* Modificar el precio ofrecido durante el Live.
* Buscar un cliente.
* Crear rápidamente un cliente nuevo.
* Registrar el precio acordado.
* Reservar el producto.
* Visualizar las últimas reservas.
* Consultar pedidos generados durante el Live.

El Modo Live deberá priorizar:

* Rapidez.
* Simplicidad.
* Pocos pasos.
* Claridad.
* Reducción de errores.

---

# Clientes

Los clientes serán registrados dentro de LiveSales.

Los principales datos serán:

* Nombre.
* Usuario de TikTok.
* Número de WhatsApp.

Durante un Live deberá existir un registro rápido.

Ejemplo:

Nombre: María
TikTok: @marialpz
WhatsApp: todavía no registrado

El WhatsApp podrá completarse posteriormente cuando el cliente contacte al negocio.

Los clientes podrán buscarse mediante:

* Nombre.
* Usuario de TikTok.
* WhatsApp.

---

# Cliente nuevo y recurrente

Un cliente será considerado recurrente cuando tenga al menos una compra anterior completada.

Los pedidos CANCELADOS no se considerarán compras completadas.

Esta información será utilizada para determinadas reglas comerciales, como el beneficio de primera compra para paquetería.

---

# Productos

En el MVP cada producto representa una única unidad física vendible.

Ejemplo:

R025 → Vestido rosado → una unidad.

Si existen tres productos físicamente iguales deberán registrarse independientemente:

R025
R026
R027

El stock múltiple dentro de un mismo registro queda fuera del MVP.

Estados operativos:

* DISPONIBLE
* RESERVADO
* VENDIDO
* INACTIVO

---

# Estado de conservación

Cada producto podrá tener:

* NUEVO_SIN_USO
* COMO_NUEVO
* BUEN_ESTADO
* CON_DETALLES

Cuando corresponda podrá registrarse una descripción.

Ejemplo:

Estado: CON_DETALLES

Detalle:

Pequeña mancha en la manga izquierda.

El estado de conservación no determinará automáticamente el precio.

---

# Precios

LiveSales diferenciará tres conceptos.

## Precio base

Precio de referencia establecido inicialmente para el producto.

Ejemplo:

Bs 20.

## Precio Live

Precio ofrecido durante una transmisión específica.

Ejemplo:

Precio base: Bs 20

Precio Live: Bs 15

## Precio acordado

Precio finalmente aceptado entre vendedor y cliente.

Ejemplo:

Precio base: Bs 20

Precio Live: Bs 15

Precio acordado: Bs 13

El precio acordado será utilizado en el pedido.

Los pedidos conservarán este precio históricamente.

---

# Reservas

Una reserva deberá identificar:

* Producto.
* Cliente.
* Live.
* Usuario vendedor.
* Fecha y hora.
* Estado.
* Precio acordado.

Estados:

* ACTIVA
* CONFIRMADA
* CANCELADA
* EXPIRADA

Una reserva ACTIVA representa una intención de compra que todavía no fue confirmada definitivamente por el cliente.

Cuando el cliente confirma expresamente que desea continuar con la compra:

* Reserva → CONFIRMADA.
* Pedido → CONFIRMADO.

Cuando el cliente deja de responder y vence el plazo de confirmación, el vendedor podrá liberar manualmente las prendas.

Al liberar una reserva vencida:

* Reserva → EXPIRADA.
* Producto → DISPONIBLE.

EXPIRADA será diferente de CANCELADA.

CANCELADA representa una cancelación explícita o administrativa.

EXPIRADA representa una reserva que perdió vigencia por falta de confirmación del cliente.

Nunca podrán existir dos reservas válidas simultáneamente para una misma unidad física.

Las reservas ACTIVA y CONFIRMADA serán consideradas reservas válidas.

Las reservas CANCELADA y EXPIRADA no bloquearán el producto.

---

# Seguimiento de confirmación

Después de realizar reservas durante un Live, el cliente podrá contactar al negocio mediante WhatsApp enviando una captura de las prendas.

El vendedor explicará:

* Modalidades de entrega.
* Lugares de entrega.
* Funcionamiento de la paquetería.
* Formas de pago.
* Condiciones de la compra.

Después de proporcionar esta información, el vendedor podrá registrar que se solicitó la confirmación del pedido.

En ese momento comenzará un plazo inicial de:

48 horas.

Se registrarán conceptualmente:

* confirmation_requested_at
* confirmation_deadline_at

Durante ese periodo LiveSales permitirá registrar hasta dos recordatorios manuales.

Ejemplo:

Día 1:

Primer recordatorio.

Día 2:

Último recordatorio.

Los mensajes continuarán enviándose manualmente mediante WhatsApp durante el MVP.

LiveSales únicamente registrará que el recordatorio fue realizado.

Podrá conservar:

* Cantidad de recordatorios.
* Fecha y hora del último recordatorio.

Si el cliente no confirma dentro del plazo, el sistema mostrará que el pedido está vencido.

En el MVP no será obligatorio liberar automáticamente las prendas en segundo plano.

El vendedor ejecutará manualmente la acción:

Liberar por falta de respuesta.

Al realizarla:

* Reservas ACTIVA → EXPIRADA.
* Productos RESERVADO → DISPONIBLE.
* Si no quedan reservas válidas, Pedido → CANCELADO.
* Motivo de cancelación → SIN_RESPUESTA.

El vendedor podrá extender manualmente el plazo cuando el cliente solicite más tiempo.

---

# Pedidos

Un cliente podrá reservar varios productos durante un mismo Live.

Las reservas se agruparán dentro de un pedido asociado a:

* Cliente.
* Live.

Estados del pedido:

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

LISTO_PARA_ENTREGA será obligatorio.

Un pedido puede permanecer PENDIENTE mientras se espera la confirmación del cliente.

Confirmar un pedido significa que el cliente expresó claramente que desea continuar con la compra.

Confirmar el pedido no significa necesariamente que ya haya realizado un pago.

---

# Pagos

Los pagos estarán asociados a pedidos.

Un pedido podrá recibir cero, uno o varios pagos.

Métodos iniciales:

* QR
* EFECTIVO

El sistema deberá permitir:

* Pedido confirmado sin pago previo cuando corresponda.
* Pago total.
* Pago parcial.
* Varios pagos para un mismo pedido.
* Consultar historial de pagos.
* Calcular saldo pendiente.

Estados de pago conceptuales:

* PENDIENTE
* PARCIAL
* PAGADO

El estado se calculará a partir del total que debe pagar el cliente y la suma de pagos válidos registrados.

---

# Pago mediante QR

LiveSales no procesará automáticamente pagos bancarios.

El negocio continuará enviando manualmente su QR mediante WhatsApp.

Después de comprobar un pago, el vendedor registrará en LiveSales:

* Pedido.
* Método.
* Monto.
* Fecha y hora.
* Usuario que registró el pago.
* Observación opcional.
* Comprobante opcional.

---

# Formas de pago para entrega acordada

Para una ENTREGA_ACORDADA el cliente podrá:

* Pagar todo mediante QR antes de la entrega.
* Pagar una parte mediante QR y completar el saldo durante la entrega.
* No realizar pago anticipado y pagar completamente de manera presencial durante la entrega.

Por lo tanto, confirmar una compra no significa obligatoriamente que exista un pago anticipado.

Ejemplo de pago parcial:

Total pedido:

Bs 60.

Primer pago QR:

Bs 20.

Saldo:

Bs 40.

Estado:

PARCIAL.

Ejemplo sin pago previo:

Total pedido:

Bs 60.

Pagado:

Bs 0.

Saldo:

Bs 60.

Estado:

PENDIENTE.

El saldo deberá quedar completamente pagado antes de completar la entrega.

---

# Pagos para paquetería

Cuando la modalidad sea PAQUETERIA, el cliente deberá pagar completamente el monto correspondiente mediante QR antes de entregar las prendas a la paquetería.

Para PAQUETERIA no se permitirá:

* Saldo pendiente.
* Pago presencial posterior.
* Completar el monto después del despacho.

Antes del envío deberá cumplirse:

Estado pago = PAGADO.

Saldo = Bs 0.

Los pagos correspondientes al monto que debe pagar el cliente deberán haberse realizado mediante QR.

---

# Entregas

Las modalidades iniciales serán:

* ENTREGA_ACORDADA
* PAQUETERIA

Estados:

* PROGRAMADA
* COMPLETADA
* CANCELADA

Inicialmente existirán:

2 entregas máximas por día.

Este límite deberá ser configurable en el futuro.

---

# Entrega acordada

Permitirá registrar:

* Fecha.
* Hora.
* Lugar.
* Observaciones.

Antes de la entrega el pedido podrá estar:

* PENDIENTE de pago.
* PARCIAL.
* PAGADO.

El saldo podrá completarse antes o durante la entrega.

La entrega no podrá marcarse COMPLETADA mientras exista saldo pendiente.

---

# Paquetería

Permitirá registrar:

* Nombre de la paquetería.
* Sucursal o lugar.
* Fecha.
* Hora aproximada.
* Observaciones.

La paquetería utilizará uno de los cupos diarios.

Costo inicial:

Bs 2.

El pedido deberá estar pagado completamente mediante QR antes de ser entregado a la paquetería.

---

# Beneficio de primera compra

Cuando un cliente utilice PAQUETERIA en su primera compra, el negocio asumirá los Bs 2 adicionales.

Ejemplo:

Productos:

Bs 50.

Costo real de paquetería:

Bs 2.

Cliente paga:

Bs 50.

Negocio asume:

Bs 2.

Para un cliente recurrente:

Productos:

Bs 50.

Paquetería:

Bs 2.

Cliente paga:

Bs 52.

El beneficio deberá utilizarse una sola vez.

Si se asigna a un pedido que posteriormente se cancela antes de completarse, podrá quedar disponible nuevamente.

---

# Sesiones Live

Estados:

* PROGRAMADO
* ACTIVO
* FINALIZADO
* CANCELADO

Transiciones:

PROGRAMADO → ACTIVO

PROGRAMADO → CANCELADO

ACTIVO → FINALIZADO

ACTIVO → CANCELADO

FINALIZADO y CANCELADO son estados terminales durante el flujo normal.

Solo un Live ACTIVO podrá generar nuevas reservas.

---

# Reglas principales

LiveSales deberá garantizar:

1. Una unidad física no puede tener dos reservas válidas simultáneamente.
2. Un producto VENDIDO no puede volver a reservarse.
3. Un producto RESERVADO debe tener una reserva ACTIVA o CONFIRMADA.
4. Un Live no ACTIVO no puede generar nuevas reservas.
5. Un pedido sin productos válidos no puede permanecer activo.
6. Un pedido debe conservar precios históricos.
7. El saldo del pedido debe calcularse a partir de los pagos registrados.
8. Confirmar un pedido no obliga a que exista un pago anticipado para ENTREGA_ACORDADA.
9. Una paquetería debe estar pagada completamente mediante QR antes del envío.
10. Una entrega no puede completarse con saldo pendiente.
11. No pueden superarse los cupos diarios de entrega.
12. Una entrega CANCELADA libera el cupo.
13. Una reserva vencida no deberá bloquear indefinidamente una prenda.
14. EXPIRADA y CANCELADA serán estados diferentes.
15. Los recordatorios de confirmación serán manuales durante el MVP.
16. Las operaciones críticas deben validarse en el servidor.
17. Las operaciones que afectan varias entidades deberán mantener consistencia.

---

# Alcance del MVP

El MVP se concentrará en la gestión interna de ventas realizadas mediante Lives.

Incluirá:

* Productos.
* Fotografías.
* Estado de conservación.
* Negociación de precios.
* Clientes.
* Lives.
* Reservas.
* Reserva EXPIRADA.
* Pedidos.
* Seguimiento de confirmación.
* Plazo inicial de 48 horas.
* Dos recordatorios manuales.
* Extensión manual del plazo.
* Liberación manual por falta de respuesta.
* Pagos manuales.
* Pedidos confirmados sin pago previo cuando corresponda.
* Pagos parciales.
* QR como método registrado.
* Efectivo como método registrado.
* Saldo pendiente.
* Entregas.
* Paquetería.
* Cupos diarios.

---

# Fuera del alcance inicial

No incluirá:

* Integración automática con TikTok.
* Lectura automática de comentarios.
* Integración automática con WhatsApp.
* WhatsApp Business API.
* Envío automático de recordatorios.
* Liberación automática mediante procesos en segundo plano.
* Verificación bancaria automática.
* Generación bancaria automática de QR.
* Pasarela de pago.
* Aplicación móvil.
* Inteligencia artificial.
* Marketplace.
* Catálogo público.
* Reservas realizadas directamente por compradores.
* Sistema SaaS.
* Multiempresa.
* Suscripciones.
* Facturación electrónica.
* Geolocalización.
* Delivery automatizado.
* Stock múltiple dentro de un producto.
* Gestión completa de devoluciones.

---

# Visión futura

LiveSales podrá evolucionar hacia:

* Catálogo público.
* Productos disponibles del Live actual.
* Reservas realizadas por compradores.
* Integración con WhatsApp.
* Recordatorios automáticos.
* Expiración automática de reservas.
* Integración con TikTok.
* Confirmación automática de pagos.
* Pagos digitales.
* Inventario avanzado.
* Multiempresa.
* Plataforma SaaS.

Estas funcionalidades deberán desarrollarse solamente después de validar correctamente el MVP.
