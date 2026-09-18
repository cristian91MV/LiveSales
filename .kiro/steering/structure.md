---
inclusion: always
---
Estructura del proyecto LiveSales
Propósito

Este documento define las convenciones generales de organización del código de LiveSales.

El proyecto deberá respetar principalmente la estructura estándar de Laravel 12.

No deberán crearse capas, carpetas o patrones adicionales sin una necesidad concreta.

Estructura general esperada

El proyecto seguirá aproximadamente:

LiveSales/

app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
.kiro/
artisan
composer.json
package.json
Steering y Specs

Los documentos permanentes del proyecto estarán en:

.kiro/steering/

Inicialmente:

product.md
business-flow.md
business-rules.md
tech.md
structure.md

Las funcionalidades desarrolladas mediante SDD estarán en:

.kiro/specs/

Ejemplo:

.kiro/specs/authentication/

.kiro/specs/products/

.kiro/specs/customers/

.kiro/specs/live-sessions/

.kiro/specs/reservations/

.kiro/specs/orders/

.kiro/specs/payments/

.kiro/specs/deliveries/

.kiro/specs/live-mode/

Cada Spec podrá contener:

requirements.md
design.md
tasks.md
Modelos

Los modelos estarán en:

app/Models/

Convención:

Singular + PascalCase.

Ejemplos previstos:

User.php

Category.php

Product.php

Customer.php

LiveSession.php

Reservation.php

Order.php

OrderItem.php

Payment.php

Delivery.php

Las relaciones deberán definirse mediante Eloquent.

Controladores

Los controladores estarán en:

app/Http/Controllers/

Cuando resulte útil podrán organizarse por área.

Ejemplo:

app/Http/Controllers/
ProductController.php
CustomerController.php
LiveSessionController.php
ReservationController.php
OrderController.php
PaymentController.php
DeliveryController.php

La estructura definitiva dependerá de las Specs.

Los controladores no deberán contener lógica de negocio extensa.

Form Requests

Las validaciones de operaciones importantes deberán colocarse en:

app/Http/Requests/

Ejemplos:

StoreProductRequest

UpdateProductRequest

StoreCustomerRequest

CreateReservationRequest

RegisterPaymentRequest

ScheduleDeliveryRequest

Actions o Services

Las operaciones complejas podrán colocarse en una capa dedicada.

La opción preferida para operaciones específicas del negocio será inicialmente:

app/Actions/

Ejemplo:

app/Actions/
Reservations/
CreateReservationAction.php
CancelReservationAction.php

Orders/
    ConfirmOrderAction.php
    CancelOrderAction.php

Payments/
    RegisterPaymentAction.php

Deliveries/
    ScheduleDeliveryAction.php
    CompleteDeliveryAction.php

Las Actions deberán representar operaciones concretas.

No deberán crearse clases innecesarias para operaciones simples.

Enums

Los estados y valores cerrados podrán ubicarse en:

app/Enums/

Ejemplos potenciales:

ProductStatus.php

ProductCondition.php

ReservationStatus.php

OrderStatus.php

LiveStatus.php

DeliveryStatus.php

DeliveryMethod.php

PaymentMethod.php

No será obligatorio crear todos desde el primer momento.

Cada Spec determinará cuáles necesita.

Policies

Cuando la autorización dependa de una entidad concreta podrán utilizarse Policies.

Ubicación:

app/Policies/

Spatie manejará roles y permisos generales.

Las Policies podrán complementar las reglas específicas sobre recursos.

Vistas

Las vistas estarán en:

resources/views/

Se organizarán por módulo.

Ejemplo:

resources/views/
layouts/
dashboard/
products/
categories/
customers/
lives/
orders/
payments/
deliveries/

El Modo Live tendrá su propia organización.

Ejemplo:

resources/views/lives/live-mode.blade.php

o una estructura equivalente definida durante la Spec.

Componentes Blade

Los elementos reutilizables podrán convertirse en Blade Components cuando exista una reutilización real.

Ubicación estándar:

resources/views/components/

No deberá fragmentarse innecesariamente cada pequeño bloque de HTML.

JavaScript

El JavaScript propio estará principalmente en:

resources/js/

Las operaciones dinámicas del Modo Live no deberán convertirse en grandes scripts incrustados dentro de Blade.

Ejemplo conceptual:

resources/js/
live-mode.js
product-search.js
customer-search.js

La estructura exacta se decidirá conforme crezca el frontend.

CSS

Los estilos propios estarán organizados dentro del sistema de assets de Laravel.

Se utilizará Bootstrap como base.

El CSS personalizado deberá mantenerse separado de librerías externas.

Rutas

Las rutas web principales estarán en:

routes/web.php

Podrán utilizarse grupos por:

Middleware.
Roles.
Prefijos.

Ejemplo conceptual:

/dashboard

/products

/customers

/lives

/orders

/deliveries

No deberá crearse una API pública durante el MVP.

Los endpoints internos utilizados por Fetch podrán permanecer protegidos por autenticación y sesión web si esto resulta apropiado.

Migraciones

Estarán en:

database/migrations/

Cada cambio estructural de base de datos deberá realizarse mediante una migración.

No deberán realizarse cambios manuales en producción sin migraciones equivalentes.

Seeders

Estarán en:

database/seeders/

Podrán utilizarse para:

Roles.
Permisos.
Usuario administrador inicial.
Datos básicos de prueba cuando resulte apropiado.
Factories

Estarán en:

database/factories/

Se utilizarán especialmente para pruebas automatizadas.

Ejemplos:

ProductFactory

CustomerFactory

OrderFactory

Tests

Se utilizarán:

tests/Feature/

tests/Unit/

Las reglas de negocio relacionadas con base de datos se probarán principalmente mediante Feature Tests.

Ejemplo:

tests/Feature/
Reservations/
Orders/
Payments/
Deliveries/

Nomenclatura
PHP

PascalCase:

CreateReservationAction

ProductController

ReservationStatus

Variables y métodos

camelCase:

agreedPrice

registerPayment()

remainingBalance()

Base de datos

snake_case:

agreed_price

live_session_id

delivery_method

Rutas

kebab-case cuando corresponda:

/live-sessions

/listo-para-entrega

La nomenclatura deberá mantenerse consistente.

Idioma del código

Los nombres técnicos dentro del código deberán escribirse principalmente en inglés.

Ejemplos:

Product

Customer

Order

Payment

Delivery

Reservation

Los textos mostrados al usuario estarán inicialmente en español.

Esto permite mantener convenciones ampliamente utilizadas en Laravel sin afectar la interfaz para el negocio.

Comentarios

No deberán utilizarse comentarios para explicar código obvio.

Los comentarios deberán utilizarse cuando expliquen:

Una decisión de negocio poco evidente.
Una restricción técnica.
Una razón de concurrencia.
Un comportamiento que podría parecer incorrecto sin contexto.
Lógica de negocio

La lógica importante no deberá depender exclusivamente de:

JavaScript.
Blade.
Controladores.

Deberá centralizarse en backend.

Especialmente:

Reservas.
Cálculo de saldo.
Confirmación de pedidos.
Reglas de paquetería.
Cupos diarios.
Finalización de entrega.
Principio de simplicidad

LiveSales es un proyecto de portafolio y un MVP real.

La estructura deberá demostrar buenas prácticas sin introducir arquitectura innecesariamente compleja.

Se evitarán por ahora patrones como:

Microservicios.
Event sourcing.
CQRS completo.
Arquitectura distribuida.
Múltiples aplicaciones frontend.

Laravel monolítico será suficiente para el MVP.