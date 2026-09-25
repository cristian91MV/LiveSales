# Implementation Plan - Live Management

## Task 1 - Live domain foundation

- [ ] Crear `LiveStatus`.
- [ ] Crear modelo `LiveSession`.
- [ ] Crear migración `live_sessions`.
- [ ] Crear modelo `LiveProduct`.
- [ ] Crear migración `live_products`.
- [ ] Crear relación LiveSession -> LiveProducts.
- [ ] Crear relación LiveProduct -> LiveSession.
- [ ] Crear relación LiveProduct -> Product.
- [ ] Agregar relación Product -> LiveProducts.
- [ ] Configurar casts.
- [ ] Ejecutar migraciones.

---

## Task 2 - Factories

- [ ] Crear LiveSessionFactory.
- [ ] Estado por defecto PROGRAMADO.
- [ ] Crear estado active().
- [ ] Crear estado finished().
- [ ] Crear estado cancelled().
- [ ] Crear LiveProductFactory.
- [ ] Verificar mediante Tinker.

---

## Task 3 - Live validation

- [ ] Crear StoreLiveSessionRequest.
- [ ] Crear UpdateLiveSessionRequest.
- [ ] Validar name.
- [ ] Validar scheduled_at.
- [ ] Validar notes.
- [ ] Impedir que formulario defina status.
- [ ] Impedir edición de campos internos.

---

## Task 4 - Live CRUD

- [ ] Crear LiveSessionController.
- [ ] Implementar index.
- [ ] Implementar create.
- [ ] Implementar store.
- [ ] Implementar show.
- [ ] Implementar edit.
- [ ] Implementar update.
- [ ] Permitir update únicamente en PROGRAMADO.
- [ ] No implementar destroy.

---

## Task 5 - Live listing

- [ ] Ordenar Lives más recientes primero.
- [ ] Filtrar por status.
- [ ] Validar filtro mediante LiveStatus.
- [ ] Cargar live_products_count.
- [ ] Paginar 25.
- [ ] Mantener query string.

---

## Task 6 - Status Actions

- [ ] Crear StartLiveAction.
- [ ] Validar PROGRAMADO -> ACTIVO.
- [ ] Registrar started_at.
- [ ] Verificar que no exista otro ACTIVO.
- [ ] Utilizar transacción.
- [ ] Crear FinishLiveAction.
- [ ] Validar ACTIVO -> FINALIZADO.
- [ ] Registrar ended_at.
- [ ] Crear CancelLiveAction.
- [ ] Permitir PROGRAMADO -> CANCELADO.
- [ ] Permitir ACTIVO -> CANCELADO.
- [ ] Registrar ended_at únicamente si estaba ACTIVO.
- [ ] Rechazar transiciones inválidas.

---

## Task 7 - Status endpoints

- [ ] Crear start endpoint.
- [ ] Crear finish endpoint.
- [ ] Crear cancel endpoint.
- [ ] Capturar errores de dominio.
- [ ] Mostrar mensajes comprensibles al usuario.

---

## Task 8 - LiveProduct validation

- [ ] Crear StoreLiveProductRequest.
- [ ] Crear UpdateLiveProductRequest.
- [ ] Validar product_id existente.
- [ ] Validar live_price >= 0.
- [ ] Validar máximo dos decimales.

---

## Task 9 - Add Live Product

- [ ] Crear AddLiveProductAction.
- [ ] Permitir PROGRAMADO.
- [ ] Permitir ACTIVO.
- [ ] Rechazar FINALIZADO.
- [ ] Rechazar CANCELADO.
- [ ] Exigir Product DISPONIBLE.
- [ ] Rechazar combinación duplicada.
- [ ] Crear LiveProduct.
- [ ] No modificar Product.status.
- [ ] No modificar Product.base_price.

---

## Task 10 - Update Live Product Price

- [ ] Crear UpdateLiveProductPriceAction.
- [ ] Permitir PROGRAMADO.
- [ ] Permitir ACTIVO.
- [ ] Rechazar FINALIZADO.
- [ ] Rechazar CANCELADO.
- [ ] Modificar únicamente live_price.
- [ ] Validar pertenencia LiveProduct -> LiveSession.

---

## Task 11 - Remove Live Product

- [ ] Crear RemoveLiveProductAction.
- [ ] Permitir PROGRAMADO.
- [ ] Permitir ACTIVO.
- [ ] Rechazar FINALIZADO.
- [ ] Rechazar CANCELADO.
- [ ] Validar pertenencia.
- [ ] Eliminar únicamente LiveProduct.
- [ ] Conservar Product.

---

## Task 12 - LiveProduct controller and routes

- [ ] Crear LiveProductController.
- [ ] Implementar store.
- [ ] Implementar update.
- [ ] Implementar destroy.
- [ ] Registrar rutas anidadas.
- [ ] Proteger mediante auth + active.

---

## Task 13 - LiveSession routes

- [ ] Registrar lives.index.
- [ ] Registrar lives.create.
- [ ] Registrar lives.store.
- [ ] Registrar lives.show.
- [ ] Registrar lives.edit.
- [ ] Registrar lives.update.
- [ ] Registrar lives.start.
- [ ] Registrar lives.finish.
- [ ] Registrar lives.cancel.
- [ ] No crear lives.destroy.
- [ ] Permitir Administrador y Vendedor.
- [ ] Aplicar whereNumber cuando corresponda.

---

## Task 14 - Live views

- [ ] Crear lives/index.blade.php.
- [ ] Crear lives/create.blade.php.
- [ ] Crear lives/edit.blade.php.
- [ ] Crear lives/show.blade.php.
- [ ] Mostrar badges por status.
- [ ] Mostrar fechas.
- [ ] Mostrar productos.
- [ ] Mostrar live_price.
- [ ] Mostrar base_price.
- [ ] Mostrar acciones según estado.

---

## Task 15 - Live product interface

- [ ] Mostrar productos DISPONIBLE elegibles.
- [ ] Excluir productos ya asociados.
- [ ] Permitir agregar producto.
- [ ] Sugerir base_price como precio Live.
- [ ] Permitir actualizar precio.
- [ ] Permitir retirar producto.
- [ ] Ocultar/deshabilitar gestión en FINALIZADO.
- [ ] Ocultar/deshabilitar gestión en CANCELADO.

---

## Task 16 - Navbar integration

- [ ] Agregar enlace Lives.
- [ ] Visible para Administrador.
- [ ] Visible para Vendedor.
- [ ] Mantener permisos existentes del navbar.

---

## Task 17 - Manual Live checkpoint

- [ ] Admin puede abrir Lives.
- [ ] Vendedor puede abrir Lives.
- [ ] Crear Live sin fecha.
- [ ] Crear Live con fecha.
- [ ] Estado inicial PROGRAMADO.
- [ ] Editar PROGRAMADO.
- [ ] Iniciar Live.
- [ ] started_at registrado.
- [ ] Intentar iniciar segundo Live mientras existe ACTIVO.
- [ ] Finalizar Live.
- [ ] ended_at registrado.
- [ ] Cancelar PROGRAMADO.
- [ ] Cancelar ACTIVO.
- [ ] No editar FINALIZADO.
- [ ] No editar CANCELADO.
- [ ] Guest redirigido.

---

## Task 18 - Manual Product checkpoint

- [ ] Agregar producto DISPONIBLE a PROGRAMADO.
- [ ] Agregar producto DISPONIBLE a ACTIVO.
- [ ] Verificar live_price.
- [ ] Verificar base_price intacto.
- [ ] Verificar Product sigue DISPONIBLE.
- [ ] Intentar agregar producto duplicado.
- [ ] Intentar agregar INACTIVO.
- [ ] Actualizar live_price.
- [ ] Retirar producto.
- [ ] Verificar Product sigue existiendo.
- [ ] No agregar producto a FINALIZADO.
- [ ] No retirar de FINALIZADO.
- [ ] No editar precio de FINALIZADO.
- [ ] Repetir restricciones con CANCELADO.

---

## Task 19 - Live Management Tests

Crear:

```text
tests/Feature/Lives/LiveManagementTest.php

- [ ] Admin puede listar.
- [ ] Vendedor puede listar.
- [ ] Crear Live.
- [ ] Estado inicial PROGRAMADO.
- [ ] scheduled_at nullable.
- [ ] Editar PROGRAMADO.
- [ ] Rechazar edición ACTIVO.
- [ ] Rechazar edición FINALIZADO.
- [ ] Rechazar edición CANCELADO.
- [ ] Filtrar por status.
- [ ] Paginar 25.
- [ ] Guest redirigido.
- [ ] Confirmar ausencia de lives.destroy.
Task 20 - Status Transition Tests
Crear:
tests/Feature/Lives/LiveStatusTransitionTest.php

- [ ] PROGRAMADO -> ACTIVO.
- [ ] started_at se registra.
- [ ] No permite dos ACTIVO.
- [ ] ACTIVO -> FINALIZADO.
- [ ] ended_at se registra.
- [ ] PROGRAMADO -> CANCELADO.
- [ ] ACTIVO -> CANCELADO.
- [ ] CANCELACIÓN activa registra ended_at.
- [ ] FINALIZADO no puede iniciar.
- [ ] CANCELADO no puede iniciar.
- [ ] FINALIZADO no puede cancelar.
- [ ] CANCELADO no puede volver a cancelarse.
Task 21 - Live Product Tests
Crear:
tests/Feature/Lives/LiveProductManagementTest.php

- [ ] Agregar DISPONIBLE.
- [ ] Guardar live_price.
- [ ] Product.status permanece DISPONIBLE.
- [ ] Product.base_price permanece igual.
- [ ] Rechazar duplicado.
- [ ] Rechazar INACTIVO.
- [ ] Rechazar RESERVADO.
- [ ] Rechazar VENDIDO.
- [ ] Agregar producto durante ACTIVO.
- [ ] Rechazar agregado en FINALIZADO.
- [ ] Rechazar agregado en CANCELADO.
- [ ] Actualizar live_price.
- [ ] Retirar LiveProduct.
- [ ] Product permanece existente.
- [ ] Rechazar modificación en FINALIZADO.
- [ ] Rechazar modificación en CANCELADO.
- [ ] Validar pertenencia LiveProduct -> LiveSession.
Task 22 - Full regression validation
Ejecutar:
php artisan test tests/Feature/Lives
php artisan test
php artisan route:list
php artisan migrate:status
npm run build

- [ ] Tests Live en verde.
- [ ] Suite completa en verde.
- [ ] Rutas correctas.
- [ ] Migraciones Ran.
- [ ] Build correcto.
- [ ] Authentication sigue funcionando.
- [ ] Catalog sigue funcionando.
- [ ] Customers sigue funcionando.
Task 23 - Final checkpoint
- [ ] Revisar requirements.
- [ ] Revisar design.
- [ ] Revisar tasks.
- [ ] Ejecutar git diff --check.
- [ ] Revisar git status.
- [ ] Confirmar que .env no se versiona.
- [ ] Marcar tareas completadas.
- [ ] Commit de implementación.
- [ ] Push de feature/live-management.
- [ ] Merge a main.
- [ ] Push de main.
- [ ] Confirmar working tree clean.
