<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('livesales.admin_name');
        $email = config('livesales.admin_email');
        $password = config('livesales.admin_password');

        if (empty($name) || empty($email) || empty($password)) {
            throw new RuntimeException(
                'Debes definir ADMIN_NAME, ADMIN_EMAIL y ADMIN_PASSWORD en el archivo .env.'
            );
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'is_active' => true,
            ]
        );

        $user->syncRoles(['Administrador']);
    }
}
