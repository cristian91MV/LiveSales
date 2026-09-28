<?php

namespace Database\Factories;

use App\Models\LiveSession;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiveProduct>
 */
class LiveProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'live_session_id' => LiveSession::factory(),

            'product_id' => Product::factory(),

            'live_price' => fake()->randomFloat(
                2,
                5,
                200
            ),
        ];
    }
}
