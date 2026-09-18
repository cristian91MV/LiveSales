---
inclusion: always
---
# LiveSales
Descripción del producto

LiveSales es un sistema web orientado a pequeños negocios y emprendimientos que realizan ventas de productos mediante transmisiones en vivo en redes sociales, especialmente TikTok Live.

El sistema busca centralizar y simplificar la gestión de:

Productos.
Clientes.
Sesiones Live.
Reservas.
Pedidos.
Pagos.
Entregas.

LiveSales no pretende sustituir a TikTok ni realizar la transmisión dentro de la plataforma.

Su función principal es servir como herramienta interna de gestión mientras el vendedor realiza un Live y posteriormente coordina el pago y la entrega con el cliente mediante canales externos como WhatsApp.

La primera versión será utilizada principalmente de forma local desde una computadora o laptop.

Problema

Los vendedores que realizan ventas mediante transmisiones en vivo suelen gestionar sus operaciones utilizando:

Comentarios del Live.
Mensajes de WhatsApp.
Capturas de pantalla.
Anotaciones manuales.
Hojas de cálculo.
Memoria del vendedor.

Cuando aumenta la cantidad de compradores pueden aparecer problemas como:

Dos personas intentando reservar el mismo producto.
Dificultad para determinar quién reservó primero.
Venta accidental de un producto a más de una persona.
Pérdida de información sobre reservas.
Dificultad para identificar compradores recurrentes.
Pedidos de un mismo cliente separados.
Dificultad para conocer el total que debe pagar un cliente.
Cambios de precio durante el Live sin registro.
Dificultad para recordar el precio finalmente acordado.
Dificultad para saber cuánto pagó un cliente y cuánto todavía debe.
Confusión entre pagos totales y parciales.
Desorganización al programar entregas.
Sobrepasar los cupos diarios de entrega.
Dificultad para controlar pedidos enviados mediante paquetería.
Falta de estadísticas sobre ventas y descuentos.
Solución propuesta

LiveSales centralizará el proceso de venta mediante Live.

El flujo general será:

Producto
→ Live
→ Cliente
→ Reserva
→ Pedido
→ Pago
→ Entrega
→ Venta completada

Durante una transmisión, el vendedor podrá:

Buscar rápidamente un producto.
Consultar su información.
Verificar disponibilidad.
Consultar su precio.
Negociar un nuevo precio.
Identificar o registrar rápidamente a un cliente.
Reservar el producto.
Agrupar varias reservas dentro de un mismo pedido.

Después del Live, el vendedor podrá continuar el proceso mediante WhatsApp.

El cliente podrá enviar una captura de la prenda o prendas que reservó y el vendedor podrá localizar su pedido, confirmar el total y proporcionar externamente el QR utilizado por el negocio.

LiveSales no procesará automáticamente el QR en el MVP.

El sistema únicamente registrará los pagos realizados y calculará automáticamente:

Total del pedido.
Total pagado.
Saldo pendiente.
Estado del pago.
Usuarios objetivo

LiveSales está dirigido principalmente a:

Pequeños emprendimientos.
Vendedores independientes.
Tiendas que venden mediante TikTok Live.
Negocios de ropa nueva.
Negocios de ropa de segunda mano.
Vendedores de accesorios.
Vendedores de calzado.
Vendedores de cosméticos.
Vendedores de juguetes.
Bazares.
Otros negocios que utilicen Lives como canal de venta.

El sistema no deberá diseñarse exclusivamente para ropa infantil.

Roles iniciales
Administrador

Podrá:

Gestionar usuarios.
Gestionar roles y permisos.
Gestionar categorías.
Gestionar productos.
Gestionar clientes.
Crear sesiones Live.
Iniciar sesiones Live.
Finalizar sesiones Live.
Cancelar sesiones Live.
Asociar productos a Lives.
Gestionar reservas.
Gestionar pedidos.
Registrar pagos.
Gestionar entregas.
Gestionar configuraciones.
Consultar estadísticas.
Vendedor

Podrá:

Consultar productos.
Registrar clientes.
Crear clientes rápidos durante un Live.
Utilizar el Modo Live.
Crear reservas.
Cancelar reservas permitidas.
Agregar productos a pedidos.
Consultar pedidos.
Confirmar pedidos.
Registrar pagos.
Consultar saldos pendientes.
Marcar pedidos como LISTO_PARA_ENTREGA.
Registrar entregas.
Completar entregas.
Crear sesiones Live.
Iniciar sesiones Live.
Finalizar sesiones Live.
Cancelar sesiones Live.
Asociar productos a Lives PROGRAMADOS o ACTIVOS.
Módulos del MVP

La primera versión incluirá:

Autenticación.
Usuarios y roles.
Categorías.
Productos.
Clientes.
Sesiones Live.
Modo Live.
Reservas.
Pedidos.
Pagos.
Entregas.
Dashboard básico.
Configuración básica.
Característica diferenciadora

La funcionalidad principal será el Modo Live.

Esta interfaz deberá estar diseñada para realizar operaciones rápidamente mientras se desarrolla una transmisión.

El vendedor deberá poder:

Buscar un producto por código.
Consultar sus fotografías.
Consultar nombre y talla.
Ver su estado de conservación.
Consultar posibles detalles o defectos.
Ver su disponibilidad.
Consultar su precio base.
Modificar el precio ofrecido durante el Live.
Buscar un cliente.
Crear rápidamente un cliente nuevo.
Registrar el precio acordado.
Reservar el producto.
Visualizar las últimas reservas.
Consultar pedidos generados durante el Live.

El Modo Live deberá priorizar:

Rapidez.
Simplicidad.
Pocos pasos.
Claridad.
Reducción de errores.
Clientes

Los clientes serán registrados dentro de LiveSales.

Los principales datos serán:

Nombre.
Usuario de TikTok.
Número de WhatsApp.

Durante un Live deberá existir un registro rápido.

Ejemplo:

Nombre: María
TikTok: @marialpz
WhatsApp: todavía no registrado

El WhatsApp podrá completarse posteriormente cuando el cliente contacte al negocio.

Los clientes podrán buscarse mediante:

Nombre.
Usuario de TikTok.
WhatsApp.
Cliente nuevo y recurrente

Un cliente será considerado recurrente cuando tenga al menos una compra anterior completada.

Los pedidos cancelados no se considerarán compras completadas.

Esta información será utilizada para determinadas reglas comerciales, como el beneficio de primera compra para paquetería.

Productos

En el MVP cada producto representa una única unidad física vendible.

Ejemplo:

R025 → Vestido rosado → una unidad.

Si existen tres productos físicamente iguales deberán registrarse independientemente:

R025
R026
R027

El stock múltiple dentro de un mismo registro queda fuera del MVP.

Estados operativos:

DISPONIBLE
RESERVADO
VENDIDO
INACTIVO
Estado de conservación

Cada producto podrá tener:

NUEVO_SIN_USO
COMO_NUEVO
BUEN_ESTADO
CON_DETALLES

Cuando corresponda podrá registrarse una descripción.

Ejemplo:

Estado: CON_DETALLES

Detalle:

Pequeña mancha en la manga izquierda.

El estado de conservación no determinará automáticamente el precio.

Precios

LiveSales diferenciará tres conceptos.

Precio base

Precio de referencia establecido inicialmente para el producto.

Ejemplo:

Bs 20.

Precio Live

Precio ofrecido durante una transmisión específica.

Ejemplo:

Precio base: Bs 20

Precio Live: Bs 15

Precio acordado

Precio finalmente aceptado entre vendedor y cliente.

Ejemplo:

Precio base: Bs 20

Precio Live: Bs 15

Precio acordado: Bs 13

El precio acordado será utilizado en el pedido.

Los pedidos conservarán este precio históricamente.

Reservas

Una reserva deberá identificar:

Producto.
Cliente.
Live.
Usuario vendedor.
Fecha.
Hora.
Estado.
Precio acordado.

Estados:

ACTIVA
CONFIRMADA
CANCELADA

Nunca podrán existir dos reservas válidas simultáneamente para una misma unidad física.

Pedidos

Un cliente podrá reservar varios productos durante un mismo Live.

Las reservas se agruparán dentro de un pedido asociado a:

Cliente.
Live.

Estados del pedido:

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

LISTO_PARA_ENTREGA será obligatorio.

Pagos

Los pagos estarán asociados a pedidos.

Un pedido podrá recibir uno o varios pagos.

Métodos iniciales:

QR
EFECTIVO

El sistema deberá permitir:

Pago total.
Pago parcial.
Varios pagos para un mismo pedido.
Consultar historial de pagos.
Calcular saldo pendiente.

Estados de pago conceptuales:

PENDIENTE
PARCIAL
PAGADO

El estado se calculará a partir del total que debe pagar el cliente y la suma de pagos válidos registrados.

Pago mediante QR

LiveSales no procesará automáticamente pagos bancarios.

El negocio continuará enviando manualmente su QR mediante WhatsApp.

Después de comprobar un pago, el vendedor registrará en LiveSales:

Pedido.
Método.
Monto.
Fecha y hora.
Usuario que registró el pago.
Observación opcional.
Comprobante opcional.
Pagos parciales

Para una ENTREGA_ACORDADA el cliente podrá:

Pagar todo mediante QR.
Pagar una parte mediante QR y completar el saldo al realizar la entrega.

Ejemplo:

Total pedido:

Bs 60.

Primer pago QR:

Bs 20.

Saldo:

Bs 40.

Estado:

PARCIAL.

Posteriormente el saldo podrá pagarse mediante:

QR.
Efectivo.
Pagos para paquetería

Cuando la modalidad sea PAQUETERIA, el cliente deberá pagar completamente el monto correspondiente antes de que el pedido pueda ser enviado a paquetería.

No se permitirá completar una entrega por PAQUETERIA mientras exista saldo pendiente.

Entregas

Las modalidades iniciales serán:

ENTREGA_ACORDADA
PAQUETERIA

Estados:

PROGRAMADA
COMPLETADA
CANCELADA

Inicialmente existirán:

2 entregas máximas por día.

Este límite deberá ser configurable en el futuro.

Entrega acordada

Permitirá registrar:

Fecha.
Hora.
Lugar.
Observaciones.

Podrá existir saldo pendiente antes de la entrega.

El saldo deberá completarse durante o antes de finalizar la entrega.

Paquetería

Permitirá registrar:

Nombre de la paquetería.
Sucursal o lugar.
Fecha.
Hora aproximada.
Observaciones.

La paquetería utilizará uno de los cupos diarios.

Costo inicial:

Bs 2.

Beneficio de primera compra

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

Sesiones Live

Estados:

PROGRAMADO
ACTIVO
FINALIZADO
CANCELADO

Transiciones:

PROGRAMADO → ACTIVO

PROGRAMADO → CANCELADO

ACTIVO → FINALIZADO

ACTIVO → CANCELADO

FINALIZADO y CANCELADO son estados terminales.

Solo un Live ACTIVO podrá generar nuevas reservas.

Reglas principales

LiveSales deberá garantizar:

Una unidad física no puede tener dos reservas válidas simultáneamente.
Un producto VENDIDO no puede volver a reservarse.
Un producto RESERVADO debe tener una reserva válida.
Un Live no ACTIVO no puede generar nuevas reservas.
Un pedido sin productos no puede permanecer activo.
Un pedido debe conservar precios históricos.
El saldo del pedido debe calcularse a partir de los pagos registrados.
Una paquetería no puede completarse con saldo pendiente.
No pueden superarse los cupos diarios de entrega.
Una entrega CANCELADA libera el cupo.
Las operaciones críticas deben validarse en el servidor.
Las operaciones que afectan varias entidades deberán mantener consistencia.
Alcance del MVP

El MVP se concentrará en la gestión interna de ventas realizadas mediante Lives.

Incluirá:

Productos.
Fotografías.
Estado de conservación.
Negociación de precios.
Clientes.
Lives.
Reservas.
Pedidos.
Pagos manuales.
Pagos parciales.
QR como método registrado.
Efectivo como método registrado.
Saldo pendiente.
Entregas.
Paquetería.
Cupos diarios.
Fuera del alcance inicial

No incluirá:

Integración automática con TikTok.
Lectura automática de comentarios.
Integración automática con WhatsApp.
WhatsApp Business API.
Verificación bancaria automática.
Generación bancaria automática de QR.
Pasarela de pago.
Aplicación móvil.
Inteligencia artificial.
Marketplace.
Catálogo público.
Reservas realizadas directamente por compradores.
Sistema SaaS.
Multiempresa.
Suscripciones.
Facturación electrónica.
Geolocalización.
Delivery automatizado.
Stock múltiple dentro de un producto.
Gestión completa de devoluciones.
Visión futura

LiveSales podrá evolucionar hacia:

Catálogo público.
Productos disponibles del Live actual.
Reservas realizadas por compradores.
Integración con WhatsApp.
Integración con TikTok.
Confirmación automática de pagos.
Pagos digitales.
Inventario avanzado.
Multiempresa.
Plataforma SaaS.

Estas funcionalidades deberán desarrollarse solamente después de validar correctamente el MVP.
