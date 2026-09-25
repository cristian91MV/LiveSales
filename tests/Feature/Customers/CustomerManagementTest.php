<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $vendor;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

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

    public function test_admin_can_view_customers(): void
    {
        Customer::factory()->create([
            'name' => 'Cliente Admin',
        ]);

        $this->actingAs($this->admin)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertViewIs('customers.index')
            ->assertSee('Cliente Admin');
    }

    public function test_vendor_can_view_customers(): void
    {
        Customer::factory()->create([
            'name' => 'Cliente Vendedor',
        ]);

        $this->actingAs($this->vendor)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Cliente Vendedor');
    }

    public function test_admin_can_create_customer(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('customers.store'), [
                'name' => 'María Pérez',
                'tiktok_username' => 'mariaperez',
                'whatsapp' => '59170000001',
            ]);

        $customer = Customer::where(
            'name',
            'María Pérez'
        )->firstOrFail();

        $response->assertRedirect(
            route('customers.show', $customer)
        );

        $this->assertDatabaseHas('customers', [
            'name' => 'María Pérez',
            'tiktok_username' => 'mariaperez',
            'whatsapp' => '59170000001',
        ]);
    }

    public function test_vendor_can_create_customer(): void
    {
        $response = $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente del Live',
            ]);

        $customer = Customer::where(
            'name',
            'Cliente del Live'
        )->firstOrFail();

        $response->assertRedirect(
            route('customers.show', $customer)
        );

        $this->assertDatabaseHas('customers', [
            'name' => 'Cliente del Live',
        ]);
    }

    public function test_customer_name_is_required(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => '',
            ])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('customers', 0);
    }

    public function test_tiktok_and_whatsapp_are_optional(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente mínimo',
                'tiktok_username' => '',
                'whatsapp' => '',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'name' => 'Cliente mínimo',
            'tiktok_username' => null,
            'whatsapp' => null,
        ]);
    }

    public function test_customer_can_be_updated(): void
    {
        $customer = Customer::factory()
            ->minimal()
            ->create([
                'name' => 'Cliente Inicial',
            ]);

        $response = $this->actingAs($this->vendor)
            ->put(
                route('customers.update', $customer),
                [
                    'name' => 'Cliente Actualizado',
                    'tiktok_username' => '@ClienteNuevo',
                    'whatsapp' => '+591 7000-0002',
                ]
            );

        $response->assertRedirect(
            route('customers.show', $customer)
        );

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Cliente Actualizado',
            'tiktok_username' => 'clientenuevo',
            'whatsapp' => '59170000002',
        ]);
    }

    public function test_created_at_does_not_change_when_customer_is_updated(): void
    {
        $customer = Customer::factory()
            ->minimal()
            ->create([
                'name' => 'Cliente Original',
            ]);

        $originalCreatedAt = $customer
            ->created_at
            ->copy();

        $this->actingAs($this->admin)
            ->put(
                route('customers.update', $customer),
                [
                    'name' => 'Nuevo Nombre',
                    'tiktok_username' => '',
                    'whatsapp' => '',
                ]
            )
            ->assertSessionHasNoErrors();

        $customer->refresh();

        $this->assertTrue(
            $customer->created_at->equalTo(
                $originalCreatedAt
            )
        );
    }

    public function test_customer_destroy_route_does_not_exist(): void
    {
        $this->assertFalse(
            Route::has('customers.destroy')
        );
    }

    public function test_guest_is_redirected_from_customers(): void
    {
        $this->get(route('customers.index'))
            ->assertRedirect(route('login'));

        $this->get(route('customers.create'))
            ->assertRedirect(route('login'));
    }
}
