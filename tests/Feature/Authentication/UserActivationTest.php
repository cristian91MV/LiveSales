<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserActivationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

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
    }

    public function test_only_active_admin_cannot_be_deactivated(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(
            route('users.deactivate', $this->admin)
        );

        $this->admin->refresh();

        $this->assertTrue($this->admin->is_active);
        $this->assertTrue(
            $this->admin->hasRole('Administrador')
        );
    }

    public function test_admin_can_be_deactivated_when_another_active_admin_exists(): void
    {
        $secondAdmin = User::factory()->create([
            'is_active' => true,
        ]);

        $secondAdmin->assignRole('Administrador');

        $this->actingAs($this->admin);

        $response = $this->patch(
            route('users.deactivate', $secondAdmin)
        );

        $response->assertRedirect(route('users.index'));

        $this->assertFalse(
            $secondAdmin->fresh()->is_active
        );
    }

    public function test_vendor_can_be_deactivated(): void
    {
        $vendor = User::factory()->create([
            'is_active' => true,
        ]);

        $vendor->assignRole('Vendedor');

        $this->actingAs($this->admin);

        $response = $this->patch(
            route('users.deactivate', $vendor)
        );

        $response->assertRedirect(route('users.index'));

        $this->assertFalse(
            $vendor->fresh()->is_active
        );
    }

    public function test_inactive_user_can_be_activated(): void
    {
        $vendor = User::factory()->create([
            'is_active' => false,
        ]);

        $vendor->assignRole('Vendedor');

        $this->actingAs($this->admin);

        $response = $this->patch(
            route('users.activate', $vendor)
        );

        $response->assertRedirect(route('users.index'));

        $this->assertTrue(
            $vendor->fresh()->is_active
        );
    }

    public function test_activated_user_can_login_again(): void
    {
        $vendor = User::factory()->create([
            'email' => 'vendedor@test.com',
            'password' => 'password123',
            'is_active' => false,
        ]);

        $vendor->assignRole('Vendedor');

        $this->actingAs($this->admin);

        $this->patch(
            route('users.activate', $vendor)
        );

        $this->post('/logout');

        $response = $this->post('/login', [
            'email' => 'vendedor@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs(
            $vendor->fresh()
        );
    }

    public function test_deactivating_nonexistent_user_returns_404(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(
            '/users/999999/deactivate'
        );

        $response->assertNotFound();
    }

    public function test_activating_nonexistent_user_returns_404(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(
            '/users/999999/activate'
        );

        $response->assertNotFound();
    }
}
