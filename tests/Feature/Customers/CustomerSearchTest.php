<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CustomerSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $vendor;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        Role::firstOrCreate([
            'name' => 'Vendedor',
            'guard_name' => 'web',
        ]);

        $this->vendor = User::factory()->create([
            'is_active' => true,
        ]);

        $this->vendor->assignRole('Vendedor');
    }

    public function test_customer_can_be_searched_by_name(): void
    {
        Customer::factory()->create([
            'name' => 'María Fernández',
        ]);

        Customer::factory()->create([
            'name' => 'Carlos López',
        ]);

        $this->actingAs($this->vendor)
            ->get(route('customers.index', [
                'search' => 'María',
            ]))
            ->assertOk()
            ->assertSee('María Fernández')
            ->assertDontSee('Carlos López');
    }

    public function test_customer_can_be_searched_by_tiktok(): void
    {
        Customer::factory()->create([
            'name' => 'Cliente TikTok',
            'tiktok_username' => 'marialpz',
        ]);

        Customer::factory()->create([
            'name' => 'Otro Cliente',
            'tiktok_username' => 'otrocliente',
        ]);

        $this->actingAs($this->vendor)
            ->get(route('customers.index', [
                'search' => 'marialpz',
            ]))
            ->assertOk()
            ->assertSee('Cliente TikTok')
            ->assertDontSee('Otro Cliente');
    }

    public function test_tiktok_search_accepts_at_symbol(): void
    {
        Customer::factory()->create([
            'name' => 'Cliente María',
            'tiktok_username' => 'marialpz',
        ]);

        $this->actingAs($this->vendor)
            ->get(route('customers.index', [
                'search' => '@MariaLPZ',
            ]))
            ->assertOk()
            ->assertSee('Cliente María');
    }

    public function test_customer_can_be_searched_by_whatsapp(): void
    {
        Customer::factory()->create([
            'name' => 'Cliente WhatsApp',
            'whatsapp' => '59171234567',
        ]);

        Customer::factory()->create([
            'name' => 'Otro WhatsApp',
            'whatsapp' => '59170000000',
        ]);

        $this->actingAs($this->vendor)
            ->get(route('customers.index', [
                'search' => '59171234567',
            ]))
            ->assertOk()
            ->assertSee('Cliente WhatsApp')
            ->assertDontSee('Otro WhatsApp');
    }

    public function test_whatsapp_search_accepts_formatted_number(): void
    {
        Customer::factory()->create([
            'name' => 'Cliente Formato',
            'whatsapp' => '59171234567',
        ]);

        $this->actingAs($this->vendor)
            ->get(route('customers.index', [
                'search' => '+591 7123-4567',
            ]))
            ->assertOk()
            ->assertSee('Cliente Formato');
    }

    public function test_nonexistent_search_returns_empty_result(): void
    {
        Customer::factory()->create([
            'name' => 'Cliente Existente',
        ]);

        $response = $this->actingAs($this->vendor)
            ->get(route('customers.index', [
                'search' => 'cliente-xyz-no-existe',
            ]));

        $response
            ->assertOk()
            ->assertSee(
                'No se encontraron clientes.'
            )
            ->assertDontSee(
                'Cliente Existente'
            );

        $response->assertViewHas(
            'customers',
            fn ($customers) =>
                $customers->total() === 0
        );
    }

    public function test_customers_are_paginated_by_25(): void
    {
        Customer::factory()
            ->count(26)
            ->create();

        $response = $this->actingAs($this->vendor)
            ->get(route('customers.index'));

        $response->assertOk();

        $response->assertViewHas(
            'customers',
            function ($customers) {
                return $customers->count() === 25
                    && $customers->total() === 26
                    && $customers->perPage() === 25;
            }
        );
    }
}
