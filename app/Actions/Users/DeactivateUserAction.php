<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DeactivateUserAction
{
    /**
     * Desactiva un usuario verificando que el sistema no quede sin administradores activos.
     *
     * Utiliza una transacción de base de datos con bloqueo pesimista para evitar
     * condiciones de carrera cuando múltiples solicitudes intentan desactivar
     * administradores simultáneamente.
     *
     * @param  User $user El usuario a desactivar
     * @throws RuntimeException Si la operación dejaría al sistema sin administradores activos
     */
    public function execute(User $user): void
    {
        DB::transaction(function () use ($user) {
            // Obtener todos los usuarios con rol Administrador e is_active = true
            // usando bloqueo pesimista para evitar condiciones de carrera
            $activeAdmins = User::query()
                ->whereHas('roles', fn ($query) => $query->where('name', 'Administrador'))
                ->where('is_active', true)
                ->lockForUpdate()
                ->get();

            // Filtrar excluyendo al usuario objetivo
            $otherActiveAdmins = $activeAdmins->reject(fn ($admin) => $admin->id === $user->id);

            // Si no hay otros administradores activos, rechazar la operación
            if ($otherActiveAdmins->isEmpty()) {
                throw new RuntimeException(
                    'No es posible realizar esta operación porque dejaría al sistema sin administradores activos.'
                );
            }

            // Desactivar el usuario
            $user->is_active = false;
            $user->save();
        });
    }
}
