<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MusicianProfile;
use App\Models\User;
use App\Models\Genre;
use App\Models\EventType;
use Illuminate\Support\Str;

class MusicianProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the main manager for the first profile
        $manager = User::where('email', 'manager@example.com')->first();

        if ($manager) {
            $profile1 = new MusicianProfile([
                'uuid' => Str::uuid(),
                'manager_id' => $manager->id,
                'artist_name' => 'The Cool Cats',
                'bio' => 'A cool jazz band playing smooth tunes for any occasion.',
                'location_city' => 'New York',
                'location_state' => 'NY',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'base_price_per_hour' => 250,
                'is_approved' => true,
            ]);
            $profile1->save();

            // Attach genres and event types
            $jazz = Genre::where('name', 'Jazz')->first();
            $corporate = EventType::where('name', 'Corporate Events')->first();
            $private = EventType::where('name', 'Private Parties')->first();
            if ($jazz) $profile1->genres()->attach($jazz);
            if ($corporate) $profile1->eventTypes()->attach($corporate);
            if ($private) $profile1->eventTypes()->attach($private);
        }

        // Create additional managers and profiles for variety
        $manager2 = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'manager2@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        $profile2 = new MusicianProfile([
            'uuid' => Str::uuid(),
            'manager_id' => $manager2->id,
            'artist_name' => 'Rock On',
            'bio' => 'High-energy rock band that covers classic hits and originals.',
            'location_city' => 'Los Angeles',
            'location_state' => 'CA',
            'latitude' => 34.0522,
            'longitude' => -118.2437,
            'base_price_per_hour' => 300,
            'is_approved' => true,
        ]);
        $profile2->save();

        $rock = Genre::where('name', 'Rock')->first();
        $weddings = EventType::where('name', 'Weddings')->first();
        $concerts = EventType::where('name', 'Concerts')->first();
        if ($rock) $profile2->genres()->attach($rock);
        if ($weddings) $profile2->eventTypes()->attach($weddings);
        if ($concerts) $profile2->eventTypes()->attach($concerts);

        $manager3 = User::factory()->create([
            'name' => 'DJ Mike',
            'email' => 'manager3@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        $profile3 = new MusicianProfile([
            'uuid' => Str::uuid(),
            'manager_id' => $manager3->id,
            'artist_name' => 'DJ Spark',
            'bio' => 'Electronic and Pop DJ for parties and festivals.',
            'location_city' => 'Miami',
            'location_state' => 'FL',
            'latitude' => 25.7617,
            'longitude' => -80.1918,
            'base_price_per_hour' => 150,
            'is_approved' => true,
        ]);
        $profile3->save();

        $pop = Genre::where('name', 'Pop')->first();
        $electronic = Genre::where('name', 'Electronic')->first();
        $festivals = EventType::where('name', 'Festivals')->first();
        $lounge = EventType::where('name', 'Lounge DJs')->first();
        if ($pop) $profile3->genres()->attach($pop);
        if ($electronic) $profile3->genres()->attach($electronic);
        if ($festivals) $profile3->eventTypes()->attach($festivals);
        if ($lounge) $profile3->eventTypes()->attach($lounge);
    }
}
