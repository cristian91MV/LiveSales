<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),

            'tiktok_username' => strtolower(
                ltrim(fake()->unique()->userName(), '@')
            ),

            'whatsapp' => fake()
                ->unique()
                ->numerify('5917#######'),
        ];
    }

    public function withoutTikTok(): static
    {
        return $this->state(fn (array $attributes) => [
            'tiktok_username' => null,
        ]);
    }

    public function withoutWhatsapp(): static
    {
        return $this->state(fn (array $attributes) => [
            'whatsapp' => null,
        ]);
    }

    public function minimal(): static
    {
        return $this->state(fn (array $attributes) => [
            'tiktok_username' => null,
            'whatsapp' => null,
        ]);
    }
}
