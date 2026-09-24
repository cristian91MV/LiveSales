<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $vendor;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Vendedor',
            'guard_name' => 'web',
        ]);

        $this->admin = User::factory()->create([
            'is_active' => true,
        ]);

        $this->admin->assignRole('Administrador');

        $this->vendor = User::factory()->create([
            'is_active' => true,
        ]);

        $this->vendor->assignRole('Vendedor');
    }

    public function test_user_has_exactly_one_valid_role(): void
    {
        $this->assertCount(1, $this->admin->roles);
        $this->assertTrue($this->admin->hasRole('Administrador'));

        $this->assertCount(1, $this->vendor->roles);
        $this->assertTrue($this->vendor->hasRole('Vendedor'));
    }

    public function test_admin_can_change_vendor_role_to_administrator(): void
    {
        $this->actingAs($this->admin);

        $response = $this->put(
            route('users.update', $this->vendor),
            [
                'name' => $this->vendor->name,
                'email' => $this->vendor->email,
                'password' => null,
                'password_confirmation' => null,
                'role' => 'Administrador',
            ]
        );

        $response->assertRedirect(route('users.index'));

        $this->vendor->refresh();

        $this->assertTrue(
            $this->vendor->hasRole('Administrador')
        );

        $this->assertFalse(
            $this->vendor->hasRole('Vendedor')
        );

        $this->assertCount(
            1,
            $this->vendor->roles()->get()
        );
    }

    public function test_invalid_role_is_rejected(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(
            route('users.store'),
            [
                'name' => 'Usuario Inválido',
                'email' => 'invalido@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'SuperAdministrador',
            ]
        );

        $response->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', [
            'email' => 'invalido@test.com',
        ]);
    }

    public function test_role_is_required_when_creating_user(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(
            route('users.store'),
            [
                'name' => 'Sin Rol',
                'email' => 'sinrol@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]
        );

        $response->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', [
            'email' => 'sinrol@test.com',
        ]);
    }

    public function test_vendor_cannot_view_user_management(): void
    {
        $this->actingAs($this->vendor);

        $this->get(route('users.index'))
            ->assertForbidden();

        $this->get(route('users.create'))
            ->assertForbidden();

        $this->get(route('users.edit', $this->admin))
            ->assertForbidden();
    }

    public function test_vendor_cannot_create_user(): void
    {
        $this->actingAs($this->vendor);

        $response = $this->post(
            route('users.store'),
            [
                'name' => 'Usuario Prohibido',
                'email' => 'prohibido@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'Vendedor',
            ]
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'email' => 'prohibido@test.com',
        ]);
    }

    public function test_vendor_cannot_update_user(): void
    {
        $originalName = $this->admin->name;

        $this->actingAs($this->vendor);

        $response = $this->put(
            route('users.update', $this->admin),
            [
                'name' => 'Nombre Modificado',
                'email' => $this->admin->email,
                'password' => null,
                'password_confirmation' => null,
                'role' => 'Vendedor',
            ]
        );

        $response->assertForbidden();

        $this->assertSame(
            $originalName,
            $this->admin->fresh()->name
        );

        $this->assertTrue(
            $this->admin->fresh()->hasRole('Administrador')
        );
    }

    public function test_vendor_cannot_activate_or_deactivate_users(): void
    {
        $target = User::factory()->create([
            'is_active' => true,
        ]);

        $target->assignRole('Vendedor');

        $this->actingAs($this->vendor);

        $this->patch(
            route('users.deactivate', $target)
        )->assertForbidden();

        $this->assertTrue(
            $target->fresh()->is_active
        );

        $target->update([
            'is_active' => false,
        ]);

        $this->patch(
            route('users.activate', $target)
        )->assertForbidden();

        $this->assertFalse(
            $target->fresh()->is_active
        );
    }
}
