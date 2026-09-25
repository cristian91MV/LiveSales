<?php

namespace Database\Factories;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),

            'code' => 'P' . fake()->unique()->numerify('#####'),

            'name' => fake()->words(3, true),

            'description' => fake()->optional()->sentence(),

            'size' => fake()->optional()->randomElement([
                '3-6 meses',
                '6-9 meses',
                '9-12 meses',
                'Única',
                'S',
                'M',
                'L',
            ]),

            'base_price' => fake()->randomFloat(
                2,
                5,
                500
            ),

            'condition' => ProductCondition::GOOD_CONDITION->value,

            'detail_description' => null,

            'status' => ProductStatus::AVAILABLE->value,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => ProductStatus::INACTIVE->value,
        ]);
    }

    public function withDetails(): static
    {
        return $this->state(fn () => [
            'condition' => ProductCondition::WITH_DETAILS->value,
            'detail_description' => fake()->sentence(),
        ]);
    }
}
