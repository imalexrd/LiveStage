<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\MusicianProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'client_id' => User::factory()->create(['role' => 'client'])->id,
            'musician_profile_id' => MusicianProfile::factory(),
            'event_date' => $this->faker->date(),
            'location_address' => $this->faker->address,
            'location_latitude' => $this->faker->latitude,
            'location_longitude' => $this->faker->longitude,
            'event_details' => $this->faker->sentence,
            'status' => 'pending',
        ];
    }
}
