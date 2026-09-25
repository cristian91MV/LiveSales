<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductPhoto>
 */
class ProductPhotoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),

            'path' => 'products/testing/' .
                fake()->uuid() .
                '.jpg',

            'is_primary' => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn () => [
            'is_primary' => true,
        ]);
    }
}
