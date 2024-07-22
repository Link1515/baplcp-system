<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventGroup>
 */
class EventGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'place' => fake()->title(),
            'price' => fake()->numberBetween(1, 500),
            'total_participants' => fake()->numberBetween(1, 10),
            'non_member_participants' => fake()->numberBetween(1, 10),
            'can_register_all_events' => false
        ];
    }
}
