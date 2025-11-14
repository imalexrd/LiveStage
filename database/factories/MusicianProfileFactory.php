<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'uuid' => Str::uuid(),
            'artist_name' => $this->faker->name,
            'bio' => $this->faker->paragraph,
            'location_city' => $this->faker->city,
            'location_state' => $this->faker->state,
            'base_price_per_hour' => $this->faker->numberBetween(100, 1000),
            'stripe_connect_id' => null,
        ];
    }
}
