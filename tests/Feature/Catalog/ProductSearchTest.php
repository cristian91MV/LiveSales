<?php

namespace Tests\Feature\Catalog;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $vendor;
    private Category $clothing;
    private Category $accessories;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate([
            'name' => 'Vendedor',
            'guard_name' => 'web',
        ]);

        $this->vendor = User::factory()->create([
            'is_active' => true,
        ]);

        $this->vendor->assignRole('Vendedor');

        $this->clothing = Category::factory()->create([
            'name' => 'Ropa infantil',
        ]);

        $this->accessories = Category::factory()->create([
            'name' => 'Accesorios',
        ]);
    }

    public function test_products_can_be_searched_by_code(): void
    {
        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'code' => 'R001',
            'name' => 'Vestido rosado',
        ]);

        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'code' => 'R002',
            'name' => 'Body azul',
        ]);

        $response = $this
            ->actingAs($this->vendor)
            ->get(
                route('products.index') .
                '?search=R001'
            );

        $response->assertOk();

        $response->assertSee('Vestido rosado');
        $response->assertDontSee('Body azul');
    }

    public function test_products_can_be_searched_by_name(): void
    {
        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'code' => 'R001',
            'name' => 'Vestido rosado',
        ]);

        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'code' => 'R002',
            'name' => 'Body azul',
        ]);

        $response = $this
            ->actingAs($this->vendor)
            ->get(
                route('products.index') .
                '?search=Vestido'
            );

        $response->assertOk();

        $response->assertSee('Vestido rosado');
        $response->assertDontSee('Body azul');
    }

    public function test_products_can_be_filtered_by_category(): void
    {
        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'name' => 'Vestido infantil',
        ]);

        Product::factory()->create([
            'category_id' => $this->accessories->id,
            'name' => 'Gorro infantil',
        ]);

        $response = $this
            ->actingAs($this->vendor)
            ->get(
                route('products.index') .
                '?category=' .
                $this->clothing->id
            );

        $response->assertSee('Vestido infantil');
        $response->assertDontSee('Gorro infantil');
    }

    public function test_products_can_be_filtered_by_status(): void
    {
        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'name' => 'Producto disponible',
            'status' => ProductStatus::AVAILABLE->value,
        ]);

        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'name' => 'Producto inactivo',
            'status' => ProductStatus::INACTIVE->value,
        ]);

        $response = $this
            ->actingAs($this->vendor)
            ->get(
                route('products.index') .
                '?status=' .
                ProductStatus::AVAILABLE->value
            );

        $response->assertSee('Producto disponible');
        $response->assertDontSee('Producto inactivo');
    }

    public function test_search_and_filters_can_be_combined(): void
    {
        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'code' => 'R010',
            'name' => 'Vestido especial',
            'status' => ProductStatus::AVAILABLE->value,
        ]);

        Product::factory()->create([
            'category_id' => $this->clothing->id,
            'code' => 'R011',
            'name' => 'Vestido inactivo',
            'status' => ProductStatus::INACTIVE->value,
        ]);

        Product::factory()->create([
            'category_id' => $this->accessories->id,
            'code' => 'A010',
            'name' => 'Vestido accesorio',
            'status' => ProductStatus::AVAILABLE->value,
        ]);

        $url = route('products.index') .
            '?search=Vestido' .
            '&category=' . $this->clothing->id .
            '&status=' . ProductStatus::AVAILABLE->value;

        $response = $this
            ->actingAs($this->vendor)
            ->get($url);

        $response->assertSee('Vestido especial');

        $response->assertDontSee('Vestido inactivo');

        $response->assertDontSee('Vestido accesorio');
    }

    public function test_products_are_paginated_by_25(): void
    {
        Product::factory()
            ->count(26)
            ->create([
                'category_id' => $this->clothing->id,
            ]);

        $response = $this
            ->actingAs($this->vendor)
            ->get(route('products.index'));

        $response->assertOk();

        $response->assertViewHas(
            'products',
            function ($products) {
                return $products->perPage() === 25
                    && $products->count() === 25
                    && $products->total() === 26;
            }
        );
    }
}
