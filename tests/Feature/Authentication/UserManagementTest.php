<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
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

    public function test_admin_can_view_paginated_user_list(): void
    {
        User::factory()->count(30)->create();

        $this->actingAs($this->admin);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);

        $response->assertViewHas('users', function ($users) {
            return $users->perPage() === 25
                && $users->count() === 25;
        });
    }

    public function test_admin_can_create_user_with_role(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('users.store'), [
            'name' => 'Vendedor Prueba',
            'email' => 'vendedor@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Vendedor',
        ]);

        $response->assertRedirect(route('users.index'));

        $user = User::where('email', 'vendedor@test.com')->firstOrFail();

        $this->assertTrue($user->is_active);
        $this->assertTrue($user->hasRole('Vendedor'));
    }

    public function test_created_password_is_hashed(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('users.store'), [
            'name' => 'Usuario Seguro',
            'email' => 'seguro@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Vendedor',
        ]);

        $user = User::where('email', 'seguro@test.com')->firstOrFail();

        $this->assertNotSame(
            'password123',
            $user->getRawOriginal('password')
        );

        $this->assertTrue(
            Hash::check(
                'password123',
                $user->getRawOriginal('password')
            )
        );
    }

    public function test_edit_without_password_keeps_existing_password(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
            'is_active' => true,
        ]);

        $user->assignRole('Vendedor');

        $originalPassword = $user->getRawOriginal('password');

        $this->actingAs($this->admin);

        $response = $this->put(route('users.update', $user), [
            'name' => 'Nombre Editado',
            'email' => $user->email,
            'password' => null,
            'password_confirmation' => null,
            'role' => 'Vendedor',
        ]);

        $response->assertRedirect(route('users.index'));

        $user->refresh();

        $this->assertSame(
            $originalPassword,
            $user->getRawOriginal('password')
        );

        $this->assertSame('Nombre Editado', $user->name);
    }

    public function test_duplicate_email_is_rejected_when_creating_user(): void
    {
        User::factory()->create([
            'email' => 'duplicado@test.com',
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('users.store'), [
            'name' => 'Otro Usuario',
            'email' => 'duplicado@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Vendedor',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertSame(
            1,
            User::where('email', 'duplicado@test.com')->count()
        );
    }

    public function test_duplicate_email_is_rejected_when_editing_user(): void
    {
        $firstUser = User::factory()->create([
            'email' => 'primero@test.com',
        ]);

        $firstUser->assignRole('Vendedor');

        $secondUser = User::factory()->create([
            'email' => 'segundo@test.com',
        ]);

        $secondUser->assignRole('Vendedor');

        $this->actingAs($this->admin);

        $response = $this->put(route('users.update', $secondUser), [
            'name' => $secondUser->name,
            'email' => 'primero@test.com',
            'password' => null,
            'password_confirmation' => null,
            'role' => 'Vendedor',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertSame(
            'segundo@test.com',
            $secondUser->fresh()->email
        );
    }

    public function test_created_at_does_not_change_when_user_is_edited(): void
    {
        $user = User::factory()->create();

        $user->assignRole('Vendedor');

        $createdAt = $user->created_at->copy();

        $this->actingAs($this->admin);

        $this->put(route('users.update', $user), [
            'name' => 'Nuevo Nombre',
            'email' => $user->email,
            'password' => null,
            'password_confirmation' => null,
            'role' => 'Vendedor',
        ]);

        $user->refresh();

        $this->assertTrue(
            $user->created_at->equalTo($createdAt)
        );
    }

    public function test_required_fields_are_validated_when_creating_user(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('users.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
            'role',
        ]);
    }

    public function test_last_active_admin_cannot_change_role_to_vendor(): void
    {
        $this->actingAs($this->admin);

        $response = $this->put(
            route('users.update', $this->admin),
            [
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'password' => null,
                'password_confirmation' => null,
                'role' => 'Vendedor',
            ]
        );

        $this->admin->refresh();

        $this->assertTrue(
            $this->admin->hasRole('Administrador')
        );

        $this->assertFalse(
            $this->admin->hasRole('Vendedor')
        );
    }
}
