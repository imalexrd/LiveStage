<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_date' => $this->faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
            'event_location' => $this->faker->city,
            'event_details' => $this->faker->paragraph,
            'status' => 'pending',
        ];
    }
}
