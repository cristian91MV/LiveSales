---
inclusion: always
---
Tecnologías y decisiones técnicas de LiveSales
Propósito

Este documento define las principales decisiones técnicas del MVP de LiveSales.

Las implementaciones generadas o asistidas por Kiro deberán respetar estas decisiones salvo que exista una razón técnica documentada para modificarlas.

Stack principal
Backend
PHP 8.3 o superior.
Laravel 12.
Arquitectura MVC de Laravel.
Composer para gestión de dependencias.

Laravel será responsable de:

Reglas de negocio.
Validaciones.
Autenticación.
Autorización.
Persistencia.
Transacciones.
Reservas.
Pedidos.
Pagos.
Lives.
Entregas.
Configuración del negocio.
Base de datos

Se utilizará:

MySQL.

Laravel Eloquent será el ORM principal.

La integridad de los datos tendrá prioridad sobre simplificaciones de implementación.

Las operaciones sensibles deberán utilizar transacciones cuando afecten varias entidades.

Ejemplos:

Crear una reserva.
Cancelar una reserva.
Confirmar un pedido.
Cancelar un pedido.
Registrar determinados cambios de pago.
Completar una entrega.
Concurrencia

LiveSales deberá contemplar operaciones simultáneas.

Especial atención a:

Dos vendedores reservando el mismo producto.
Dos vendedores intentando utilizar el último cupo de entrega.
Confirmación y cancelación simultáneas.
Cambios simultáneos sobre pedido y reserva.

Las operaciones críticas deberán utilizar mecanismos apropiados de MySQL y Laravel.

Cuando corresponda podrán utilizarse:

Transacciones.
Bloqueo pesimista de filas.
Validaciones dentro de la transacción.

No se deberá confiar solamente en una consulta previa como:

"¿Está disponible?"

y posteriormente guardar sin volver a comprobar.

Frontend

El MVP utilizará:

Blade.
Bootstrap.
JavaScript.
Fetch API.

No se utilizará inicialmente:

React.
Vue.
Angular.
Una SPA completa.

El objetivo es mantener una arquitectura simple y coherente con Laravel.

Modo Live

El Modo Live tendrá comportamiento dinámico.

Cuando sea razonable, las operaciones deberán realizarse mediante Fetch API sin recargar toda la página.

Ejemplos:

Buscar producto.
Buscar cliente.
Crear cliente rápido.
Modificar precio Live.
Registrar una reserva.
Consultar últimas reservas.
Actualizar pedido.

El uso de JavaScript no deberá reemplazar las validaciones del servidor.

Autenticación

LiveSales tendrá autenticación para usuarios internos.

No existirán cuentas públicas de compradores en el MVP.

Se utilizará una solución compatible y mantenida para Laravel 12.

La elección concreta del paquete o starter kit deberá mantenerse lo más cercana posible al ecosistema oficial de Laravel.

Roles y permisos

Se utilizará:

Spatie Laravel Permission.

Roles iniciales:

Administrador.
Vendedor.

Las autorizaciones deberán comprobarse en el servidor.

Ocultar botones en la interfaz no será considerado suficiente para proteger una operación.

Validaciones

Toda entrada proporcionada por usuarios deberá validarse en backend.

Cuando corresponda se utilizarán Form Requests.

Ejemplos:

Crear producto.
Crear cliente.
Registrar reserva.
Confirmar pedido.
Registrar pago.
Programar entrega.

La validación de JavaScript será únicamente complementaria.

Manejo monetario

Los valores monetarios deberán almacenarse utilizando tipos DECIMAL apropiados.

No deberán utilizarse FLOAT o DOUBLE para precios y pagos.

Ejemplos de datos monetarios:

Precio base.
Precio Live.
Precio acordado.
Precio histórico.
Costo de paquetería.
Monto pagado.
Monto asumido por el negocio.

El saldo deberá calcularse a partir de:

total del cliente - pagos válidos.

No deberá introducirse manualmente.

Pagos

LiveSales no se conectará directamente con entidades bancarias durante el MVP.

El módulo de pagos será un registro interno.

Métodos iniciales:

QR.
EFECTIVO.

Un pedido podrá tener varios pagos.

El estado:

PENDIENTE.
PARCIAL.
PAGADO.

deberá derivarse de los montos registrados y no depender de una selección manual del vendedor.

Los comprobantes serán opcionales.

Archivos e imágenes

Los productos podrán tener una o más imágenes.

Los comprobantes de pago podrán tener una imagen opcional.

Durante desarrollo local se utilizará el sistema de almacenamiento de Laravel.

Los archivos públicos deberán utilizar la estructura recomendada por Laravel.

No deberán almacenarse imágenes como datos binarios directamente dentro de MySQL.

Eliminación de datos

Los registros históricos importantes no deberán eliminarse físicamente mediante el flujo normal.

Ejemplos:

Pedidos.
Pagos.
Reservas asociadas a ventas.
Entregas.

Se utilizarán estados como:

CANCELADO.
INACTIVO.

La eliminación física deberá reservarse para casos administrativos justificados.

Auditoría y trazabilidad

Las entidades deberán conservar:

created_at.
updated_at.

Las operaciones relevantes deberán identificar al usuario responsable cuando sea necesario.

Ejemplos:

Usuario que realizó la reserva.
Usuario que registró un pago.
Usuario que completó una entrega.

El MVP no requiere todavía un sistema completo de auditoría de cada cambio de campo.

Servicios y acciones de negocio

Los controladores deberán mantenerse ligeros.

No se deberá concentrar lógica compleja directamente dentro de los controladores.

Para operaciones de negocio importantes podrán utilizarse clases dedicadas.

Ejemplos:

CreateReservationAction

CancelReservationAction

ConfirmOrderAction

CancelOrderAction

RegisterPaymentAction

ScheduleDeliveryAction

CompleteDeliveryAction

El proyecto elegirá un enfoque consistente entre Actions o Services cuando comience la implementación.

No deberán mezclarse múltiples patrones sin necesidad.

Modelos Eloquent

Los modelos representarán entidades del dominio.

Ejemplos esperados:

User
Category
Product
Customer
LiveSession
Reservation
Order
OrderItem
Payment
Delivery

El nombre exacto de las entidades y relaciones deberá definirse durante las Specs correspondientes.

Estados

Los estados deberán manejarse de forma consistente.

Cuando resulte apropiado se utilizarán PHP Enums para evitar cadenas repetidas por todo el sistema.

Posibles ejemplos:

ProductStatus

ReservationStatus

OrderStatus

LiveStatus

DeliveryStatus

PaymentMethod

DeliveryMethod

ProductCondition

La decisión definitiva deberá tomarse durante el diseño correspondiente.

Base de datos y restricciones

Además de las validaciones de Laravel, deberán utilizarse restricciones de base de datos cuando aporten integridad.

Ejemplos:

Foreign keys.
Índices únicos.
Índices para búsquedas frecuentes.

La base de datos deberá diseñarse para evitar inconsistencias incluso cuando existan operaciones simultáneas.

Búsquedas

Las búsquedas importantes deberán ser rápidas.

Especialmente:

Productos

Por:

Código.
Nombre.
Clientes

Por:

Nombre.
Usuario de TikTok.
WhatsApp.
Pedidos

Por:

Número de pedido.
Cliente.
Estado.
Rendimiento

El MVP no necesita optimización prematura.

Sin embargo se deberá evitar:

Consultas N+1.
Consultas repetitivas innecesarias.
Cargar colecciones completas cuando no sea necesario.

Se utilizarán:

Eager loading cuando corresponda.
Paginación.
Índices adecuados.
Seguridad

Como mínimo se deberán aplicar:

Protección CSRF.
Validación de entradas.
Escape adecuado en Blade.
Autorización por roles y permisos.
Hash seguro de contraseñas.
Restricciones de subida de archivos.
Validación de MIME y tamaño.
Protección contra asignación masiva.
Variables sensibles dentro de .env.

Nunca deberán escribirse credenciales directamente dentro del código fuente.

Pruebas

Se utilizará el sistema de testing compatible con Laravel.

Se priorizarán Feature Tests para reglas que interactúan con base de datos.

Las reglas críticas deberán contar con pruebas automatizadas.

Especialmente:

Una prenda no puede reservarse dos veces.
Un producto vendido no puede reservarse.
Solo un Live ACTIVO acepta reservas.
Los precios históricos no cambian.
Los pagos parciales calculan correctamente el saldo.
Paquetería exige pago completo.
No se puede superar el límite diario de entregas.
Cancelar una entrega libera cupo.
Un pedido sin productos se cancela.
Completar una entrega sincroniza pedido y productos.
Desarrollo local

El entorno inicial será:

Windows.
Laragon.
PHP 8.3 o superior.
MySQL.
Composer.
Node.js.
npm.
Git.
Kiro.

LiveSales se ejecutará inicialmente de forma local.

Ejemplo:

http://localhost

o mediante el servidor local utilizado durante desarrollo.

Uso desde otros dispositivos

Durante desarrollo podrá habilitarse temporalmente el acceso desde otros dispositivos conectados a la misma red local para realizar pruebas responsive.

Esto no se considerará despliegue de producción.

Producción

El despliegue público queda para una etapa posterior.

La futura infraestructura deberá soportar como mínimo:

PHP compatible.
Laravel 12.
MySQL.
HTTPS.
Dominio.
Almacenamiento de archivos.
Variables de entorno.
Git

Se utilizará Git desde el inicio.

El proyecto deberá almacenarse en GitHub.

Deberán versionarse:

Código fuente.
Migraciones.
Tests.
.kiro/steering/.
.kiro/specs/.

No deberán versionarse:

.env.
Dependencias instaladas.
Archivos temporales.
Credenciales.
Datos sensibles.
SDD

LiveSales utilizará Spec-Driven Development.

Antes de implementar funcionalidades importantes se deberán definir:

Requirements.
Design.
Tasks.
Implementación.
Pruebas.

Las Specs estarán ubicadas dentro de:

.kiro/specs/

Kiro podrá ayudar a convertir las reglas del negocio en diseños y tareas concretas.

Principios técnicos
Seguir las convenciones de Laravel antes de crear soluciones personalizadas.
Priorizar integridad de datos.
Mantener las reglas importantes en backend.
Mantener controladores simples.
Utilizar transacciones en operaciones críticas.
No añadir tecnologías innecesarias al MVP.
Mantener código legible.
Crear pruebas para reglas críticas.
Diseñar pensando en crecimiento futuro sin construir el SaaS antes de necesitarlo.
No permitir que Kiro invente reglas de negocio que contradigan los documentos de Steering.