<?php

namespace Tests\Feature\Catalog;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $vendor;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

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

        $this->category = Category::factory()->create([
            'name' => 'Ropa infantil',
        ]);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'category_id' => $this->category->id,
            'code' => 'R001',
            'name' => 'Vestido rosado',
            'description' => 'Vestido infantil.',
            'size' => '6-9 meses',
            'base_price' => '25.00',
            'condition' => ProductCondition::LIKE_NEW->value,
            'detail_description' => null,
            'status' => ProductStatus::AVAILABLE->value,
            'images' => [
                UploadedFile::fake()->image('vestido.jpg', 600, 600),
            ],
        ], $overrides);
    }

    public function test_admin_can_create_product(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(
                route('products.store'),
                $this->validPayload()
            );

        $product = Product::where('code', 'R001')->firstOrFail();

        $response->assertRedirect(
            route('products.show', $product)
        );

        $this->assertSame('Vestido rosado', $product->name);
        $this->assertSame('25.00', $product->base_price);
        $this->assertSame(
            ProductStatus::AVAILABLE,
            $product->status
        );

        $this->assertCount(1, $product->photos);

        $photo = $product->photos()->firstOrFail();

        $this->assertTrue($photo->is_primary);

        $this->assertTrue(
            Storage::disk('public')->exists($photo->path)
        );
    }

    public function test_product_code_must_be_unique(): void
    {
        Product::factory()->create([
            'category_id' => $this->category->id,
            'code' => 'R001',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->post(
                route('products.store'),
                $this->validPayload()
            );

        $response->assertSessionHasErrors('code');
    }

    public function test_product_requires_existing_category(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(
                route('products.store'),
                $this->validPayload([
                    'category_id' => 999999,
                ])
            );

        $response->assertSessionHasErrors('category_id');
    }

    public function test_base_price_cannot_be_negative(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(
                route('products.store'),
                $this->validPayload([
                    'base_price' => '-5',
                ])
            );

        $response->assertSessionHasErrors('base_price');
    }

    public function test_invalid_condition_is_rejected(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(
                route('products.store'),
                $this->validPayload([
                    'condition' => 'EXCELENTE',
                ])
            );

        $response->assertSessionHasErrors('condition');
    }

    public function test_with_details_requires_description(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(
                route('products.store'),
                $this->validPayload([
                    'condition' => ProductCondition::WITH_DETAILS->value,
                    'detail_description' => null,
                ])
            );

        $response->assertSessionHasErrors(
            'detail_description'
        );
    }

    public function test_product_requires_at_least_one_photo(): void
    {
        $payload = $this->validPayload();

        unset($payload['images']);

        $response = $this
            ->actingAs($this->admin)
            ->post(route('products.store'), $payload);

        $response->assertSessionHasErrors('images');
    }

    public function test_invalid_file_is_rejected_as_product_photo(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(
                route('products.store'),
                $this->validPayload([
                    'images' => [
                        UploadedFile::fake()
                            ->create(
                                'documento.pdf',
                                100,
                                'application/pdf'
                            ),
                    ],
                ])
            );

        $response->assertSessionHasErrors('images.0');
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'code' => 'R001',
            'name' => 'Nombre original',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->put(
                route('products.update', $product),
                [
                    'category_id' => $this->category->id,
                    'code' => 'R001',
                    'name' => 'Vestido actualizado',
                    'description' => 'Descripción nueva',
                    'size' => '9-12 meses',
                    'base_price' => '30.00',
                    'condition' => ProductCondition::LIKE_NEW->value,
                    'detail_description' => null,
                    'status' => ProductStatus::AVAILABLE->value,
                ]
            );

        $response->assertRedirect(
            route('products.show', $product)
        );

        $product->refresh();

        $this->assertSame(
            'Vestido actualizado',
            $product->name
        );

        $this->assertSame('30.00', $product->base_price);
    }

    public function test_created_at_does_not_change_when_product_is_updated(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'code' => 'R001',
        ]);

        $createdAt = $product->created_at->copy();

        $this
            ->actingAs($this->admin)
            ->put(
                route('products.update', $product),
                [
                    'category_id' => $this->category->id,
                    'code' => $product->code,
                    'name' => 'Producto editado',
                    'description' => null,
                    'size' => null,
                    'base_price' => '20.00',
                    'condition' => ProductCondition::GOOD_CONDITION->value,
                    'detail_description' => null,
                    'status' => ProductStatus::AVAILABLE->value,
                ]
            );

        $product->refresh();

        $this->assertTrue(
            $product->created_at->equalTo($createdAt)
        );
    }

    public function test_vendor_cannot_create_product(): void
    {
        $response = $this
            ->actingAs($this->vendor)
            ->post(
                route('products.store'),
                $this->validPayload()
            );

        $response->assertForbidden();

        $this->assertDatabaseMissing('products', [
            'code' => 'R001',
        ]);
    }

    public function test_vendor_cannot_edit_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $this
            ->actingAs($this->vendor)
            ->get(route('products.edit', $product))
            ->assertForbidden();
    }

    public function test_vendor_can_view_product_catalog(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Vestido de prueba',
        ]);

        $this
            ->actingAs($this->vendor)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Vestido de prueba');

        $this
            ->actingAs($this->vendor)
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Vestido de prueba');
    }

    public function test_guest_is_redirected_from_product_catalog(): void
    {
        $this
            ->get(route('products.index'))
            ->assertRedirect(route('login'));
    }
}
