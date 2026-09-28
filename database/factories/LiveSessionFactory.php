<?php

namespace Database\Factories;

use App\Enums\LiveStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiveSession>
 */
class LiveSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Live ' . fake()->words(3, true),

            'scheduled_at' => fake()->optional()
                ->dateTimeBetween('-2 days', '+7 days'),

            'started_at' => null,
            'ended_at' => null,

            'notes' => fake()->optional()
                ->sentence(),

            'status' => LiveStatus::SCHEDULED,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LiveStatus::ACTIVE,
            'started_at' => now(),
            'ended_at' => null,
        ]);
    }

    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LiveStatus::FINISHED,
            'started_at' => now()->subHours(2),
            'ended_at' => now()->subHour(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LiveStatus::CANCELLED,
            'started_at' => null,
            'ended_at' => null,
        ]);
    }
}
