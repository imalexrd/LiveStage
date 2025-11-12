<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MusicianProfile>
 */
class MusicianProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'artist_name' => $this->faker->name,
            'bio' => $this->faker->paragraph,
            'location_city' => $this->faker->city,
            'location_state' => $this->faker->state,
            'base_price_per_hour' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
