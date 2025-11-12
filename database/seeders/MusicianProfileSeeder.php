<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\MusicianProfile;
use App\Models\User;

class MusicianProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where('email', 'manager@example.com')->first();

        MusicianProfile::factory()->create([
            'manager_id' => $manager->id,
            'artist_name' => 'The Cool Cats',
            'bio' => 'A cool jazz band.',
            'location_city' => 'New York',
            'location_state' => 'NY',
            'base_price_per_hour' => 200,
            'is_approved' => true,
        ]);
    }
}
