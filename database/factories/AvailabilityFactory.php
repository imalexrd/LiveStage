<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'musician_profile_id' => \App\Models\MusicianProfile::factory(),
            'unavailable_date' => $this->faker->date(),
            'reason' => $this->faker->sentence(),
        ];
    }
}
