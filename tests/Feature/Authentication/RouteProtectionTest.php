<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RouteProtectionTest extends TestCase
{
    use RefreshDatabase;

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
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_user_index(): void
    {
        $response = $this->get('/users');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_user_create(): void
    {
        $response = $this->get('/users/create');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_user_edit(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));

        $response->assertRedirect(route('login'));
    }

    public function test_inactive_authenticated_user_is_logged_out_on_next_request(): void
    {
        $user = User::factory()->create([
            'is_active' => false,
        ]);

        $user->assignRole('Vendedor');

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_active_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $user->assignRole('Vendedor');

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }
}
