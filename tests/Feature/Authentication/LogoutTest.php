<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LogoutTest extends TestCase
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

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $user->assignRole('Administrador');

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_user_cannot_access_dashboard_after_logout(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $user->assignRole('Vendedor');

        $this->actingAs($user);

        $this->post('/logout');

        $this->assertGuest();

        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
