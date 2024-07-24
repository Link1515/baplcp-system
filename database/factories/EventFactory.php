<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = fake()->dateTime();
        $registerStartAt = Carbon::instance($startAt)->subDays(6);
        $registerEndAt = Carbon::instance($startAt)->subDays(5);

        return [
            'season_id' => 1,
            'start_at' => $startAt,
            'register_start_at' => $registerStartAt,
            'register_end_at' => $registerEndAt,
        ];
    }
}
