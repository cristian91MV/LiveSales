<?php

namespace Tests\Feature\Catalog;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $vendor;

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

        $this->vendor = User::factory()->create([
            'is_active' => true,
        ]);

        $this->vendor->assignRole('Vendedor');
    }

    public function test_admin_can_view_categories(): void
    {
        Category::factory()->create([
            'name' => 'Ropa infantil',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('categories.index'));

        $response->assertOk();
        $response->assertSee('Ropa infantil');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('categories.store'), [
                'name' => 'Accesorios',
            ]);

        $response->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Accesorios',
        ]);
    }

    public function test_category_name_is_required(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('categories.store'), [
                'name' => '',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::factory()->create([
            'name' => 'Ropa infantil',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->post(route('categories.store'), [
                'name' => 'Ropa infantil',
            ]);

        $response->assertSessionHasErrors('name');

        $this->assertSame(
            1,
            Category::where('name', 'Ropa infantil')->count()
        );
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Accesorios',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->put(route('categories.update', $category), [
                'name' => 'Accesorios infantiles',
            ]);

        $response->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Accesorios infantiles',
        ]);
    }

    public function test_admin_can_delete_category_without_products(): void
    {
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();

        Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_vendor_cannot_manage_categories(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->vendor)
            ->get(route('categories.index'))
            ->assertForbidden();

        $this->actingAs($this->vendor)
            ->get(route('categories.create'))
            ->assertForbidden();

        $this->actingAs($this->vendor)
            ->get(route('categories.edit', $category))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('categories.index'));

        $response->assertRedirect(route('login'));
    }
}
