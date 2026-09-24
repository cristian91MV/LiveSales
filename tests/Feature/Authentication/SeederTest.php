<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'livesales.admin_name' => 'Administrador Inicial',
            'livesales.admin_email' => 'admin@livesales.test',
            'livesales.admin_password' => 'password123',
        ]);
    }

    public function test_roles_seeder_creates_required_roles(): void
    {
        (new RolesAndPermissionsSeeder())->run();

        $this->assertDatabaseHas('roles', [
            'name' => 'Administrador',
            'guard_name' => 'web',
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Vendedor',
            'guard_name' => 'web',
        ]);
    }

    public function test_admin_seeder_creates_initial_administrator(): void
    {
        (new RolesAndPermissionsSeeder())->run();
        (new AdminUserSeeder())->run();

        $admin = User::where(
            'email',
            'admin@livesales.test'
        )->firstOrFail();

        $this->assertSame(
            'Administrador Inicial',
            $admin->name
        );

        $this->assertTrue($admin->is_active);

        $this->assertTrue(
            $admin->hasRole('Administrador')
        );
    }

    public function test_seeders_are_idempotent(): void
    {
        (new RolesAndPermissionsSeeder())->run();
        (new AdminUserSeeder())->run();

        (new RolesAndPermissionsSeeder())->run();
        (new AdminUserSeeder())->run();

        $this->assertSame(
            2,
            Role::count()
        );

        $this->assertSame(
            1,
            User::where(
                'email',
                'admin@livesales.test'
            )->count()
        );

        $admin = User::where(
            'email',
            'admin@livesales.test'
        )->firstOrFail();

        $this->assertCount(
            1,
            $admin->roles
        );

        $this->assertTrue(
            $admin->hasRole('Administrador')
        );
    }

    public function test_admin_has_timestamps(): void
    {
        (new RolesAndPermissionsSeeder())->run();
        (new AdminUserSeeder())->run();

        $admin = User::where(
            'email',
            'admin@livesales.test'
        )->firstOrFail();

        $this->assertNotNull(
            $admin->created_at
        );

        $this->assertNotNull(
            $admin->updated_at
        );
    }

    public function test_admin_seeder_fails_when_name_is_missing(): void
    {
        config([
            'livesales.admin_name' => null,
        ]);

        (new RolesAndPermissionsSeeder())->run();

        $this->expectException(RuntimeException::class);

        (new AdminUserSeeder())->run();
    }

    public function test_admin_seeder_fails_when_email_is_missing(): void
    {
        config([
            'livesales.admin_email' => null,
        ]);

        (new RolesAndPermissionsSeeder())->run();

        $this->expectException(RuntimeException::class);

        (new AdminUserSeeder())->run();
    }

    public function test_admin_seeder_fails_when_password_is_missing(): void
    {
        config([
            'livesales.admin_password' => null,
        ]);

        (new RolesAndPermissionsSeeder())->run();

        $this->expectException(RuntimeException::class);

        (new AdminUserSeeder())->run();
    }
}
