<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CustomerNormalizationTest extends TestCase
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

    public function test_tiktok_removes_at_and_converts_to_lowercase(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'María',
                'tiktok_username' => '  @MariaLPZ  ',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'name' => 'María',
            'tiktok_username' => 'marialpz',
        ]);
    }

    public function test_customer_name_is_trimmed(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => '   María Pérez   ',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'name' => 'María Pérez',
        ]);
    }

    public function test_empty_tiktok_becomes_null(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente',
                'tiktok_username' => '   ',
            ])
            ->assertSessionHasNoErrors();

        $customer = Customer::firstOrFail();

        $this->assertNull(
            $customer->tiktok_username
        );
    }

    public function test_whatsapp_is_normalized_to_digits(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente WhatsApp',
                'whatsapp' => '+591 7123-4567',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'whatsapp' => '59171234567',
        ]);
    }

    public function test_empty_whatsapp_becomes_null(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente',
                'whatsapp' => '   ',
            ])
            ->assertSessionHasNoErrors();

        $customer = Customer::firstOrFail();

        $this->assertNull(
            $customer->whatsapp
        );
    }

    public function test_duplicate_normalized_tiktok_is_rejected(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Primera María',
                'tiktok_username' => '@MariaLPZ',
            ])
            ->assertSessionHasNoErrors();

        $response = $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Segunda María',
                'tiktok_username' => 'MariaLPZ',
            ]);

        $response->assertSessionHasErrors(
            'tiktok_username'
        );

        $this->assertDatabaseCount(
            'customers',
            1
        );
    }

    public function test_duplicate_normalized_whatsapp_is_rejected(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente Uno',
                'whatsapp' => '+591 7123-4567',
            ])
            ->assertSessionHasNoErrors();

        $response = $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente Dos',
                'whatsapp' => '591 7123 4567',
            ]);

        $response->assertSessionHasErrors(
            'whatsapp'
        );

        $this->assertDatabaseCount(
            'customers',
            1
        );
    }

    public function test_duplicate_customer_names_are_allowed(): void
    {
        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'María Pérez',
            ])
            ->assertSessionHasNoErrors();

        $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'María Pérez',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount(
            'customers',
            2
        );
    }

    public function test_multiple_customers_can_have_null_contact_fields(): void
    {
        Customer::factory()
            ->count(3)
            ->minimal()
            ->create();

        $this->assertDatabaseCount(
            'customers',
            3
        );

        $this->assertSame(
            3,
            Customer::whereNull(
                'tiktok_username'
            )->count()
        );

        $this->assertSame(
            3,
            Customer::whereNull(
                'whatsapp'
            )->count()
        );
    }

    public function test_invalid_whatsapp_is_rejected(): void
    {
        $response = $this->actingAs($this->vendor)
            ->post(route('customers.store'), [
                'name' => 'Cliente',
                'whatsapp' => '591ABC123',
            ]);

        $response->assertSessionHasErrors(
            'whatsapp'
        );

        $this->assertDatabaseCount(
            'customers',
            0
        );
    }
}
