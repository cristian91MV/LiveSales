<?php

namespace Tests\Feature\Catalog;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductPhotoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'web',
        ]);

        $this->admin = User::factory()->create([
            'is_active' => true,
        ]);

        $this->admin->assignRole('Administrador');

        $this->category = Category::factory()->create();
    }

    private function createProduct(): Product
    {
        return Product::factory()->create([
            'category_id' => $this->category->id,
        ]);
    }

    private function createPhoto(
        Product $product,
        bool $primary = false
    ): ProductPhoto {
        $path = 'products/' .
            $product->id .
            '/' .
            Str::uuid() .
            '.jpg';

        Storage::disk('public')->put(
            $path,
            'fake-image'
        );

        return ProductPhoto::create([
            'product_id' => $product->id,
            'path' => $path,
            'is_primary' => $primary,
        ]);
    }

    public function test_multiple_photos_can_be_uploaded_when_creating_product(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('products.store'), [
                'category_id' => $this->category->id,
                'code' => 'R100',
                'name' => 'Producto con fotos',
                'description' => null,
                'size' => null,
                'base_price' => '25.00',
                'condition' => ProductCondition::LIKE_NEW->value,
                'detail_description' => null,
                'status' => ProductStatus::AVAILABLE->value,
                'images' => [
                    UploadedFile::fake()->image('uno.jpg'),
                    UploadedFile::fake()->image('dos.jpg'),
                    UploadedFile::fake()->image('tres.png'),
                ],
            ]);

        $product = Product::where('code', 'R100')
            ->firstOrFail();

        $response->assertRedirect(
            route('products.show', $product)
        );

        $this->assertCount(
            3,
            $product->photos()->get()
        );

        $this->assertSame(
            1,
            $product->photos()
                ->where('is_primary', true)
                ->count()
        );

        $this->assertTrue(
            $product->photos()
                ->orderBy('id')
                ->firstOrFail()
                ->is_primary
        );
    }

    public function test_admin_can_change_primary_photo(): void
    {
        $product = $this->createProduct();

        $first = $this->createPhoto(
            $product,
            true
        );

        $second = $this->createPhoto(
            $product,
            false
        );

        $response = $this
            ->actingAs($this->admin)
            ->patch(
                route(
                    'products.photos.primary',
                    [$product, $second]
                )
            );

        $response->assertRedirect(
            route('products.edit', $product)
        );

        $this->assertFalse(
            $first->fresh()->is_primary
        );

        $this->assertTrue(
            $second->fresh()->is_primary
        );

        $this->assertSame(
            1,
            $product->photos()
                ->where('is_primary', true)
                ->count()
        );
    }

    public function test_photo_can_be_deleted_when_other_photos_exist(): void
    {
        $product = $this->createProduct();

        $primary = $this->createPhoto(
            $product,
            true
        );

        $secondary = $this->createPhoto(
            $product,
            false
        );

        $path = $secondary->path;

        $response = $this
            ->actingAs($this->admin)
            ->delete(
                route(
                    'products.photos.destroy',
                    [$product, $secondary]
                )
            );

        $response->assertRedirect(
            route('products.edit', $product)
        );

        $this->assertDatabaseMissing(
            'product_photos',
            [
                'id' => $secondary->id,
            ]
        );

        $this->assertFalse(
            Storage::disk('public')->exists($path)
        );

        $this->assertTrue(
            $primary->fresh()->is_primary
        );
    }

    public function test_deleting_primary_photo_selects_another_primary(): void
    {
        $product = $this->createProduct();

        $primary = $this->createPhoto(
            $product,
            true
        );

        $secondary = $this->createPhoto(
            $product,
            false
        );

        $this
            ->actingAs($this->admin)
            ->delete(
                route(
                    'products.photos.destroy',
                    [$product, $primary]
                )
            );

        $this->assertTrue(
            $secondary->fresh()->is_primary
        );

        $this->assertSame(
            1,
            $product->photos()
                ->where('is_primary', true)
                ->count()
        );
    }

    public function test_last_photo_cannot_be_deleted(): void
    {
        $product = $this->createProduct();

        $photo = $this->createPhoto(
            $product,
            true
        );

        $response = $this
            ->actingAs($this->admin)
            ->delete(
                route(
                    'products.photos.destroy',
                    [$product, $photo]
                )
            );

        $response->assertRedirect(
            route('products.edit', $product)
        );

        $response->assertSessionHas('error');

        $this->assertDatabaseHas(
            'product_photos',
            [
                'id' => $photo->id,
            ]
        );

        $this->assertTrue(
            Storage::disk('public')->exists($photo->path)
        );
    }

    public function test_photo_from_another_product_cannot_be_set_as_primary(): void
    {
        $productA = $this->createProduct();
        $productB = $this->createProduct();

        $this->createPhoto(
            $productA,
            true
        );

        $foreignPhoto = $this->createPhoto(
            $productB,
            true
        );

        $response = $this
            ->actingAs($this->admin)
            ->patch(
                route(
                    'products.photos.primary',
                    [$productA, $foreignPhoto]
                )
            );

        $response->assertRedirect(
            route('products.edit', $productA)
        );

        $response->assertSessionHas('error');

        $this->assertTrue(
            $foreignPhoto->fresh()->is_primary
        );
    }
}
