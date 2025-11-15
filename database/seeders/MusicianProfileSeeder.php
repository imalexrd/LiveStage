<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MusicianProfile;
use App\Models\User;
use App\Models\Genre;
use App\Models\EventType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class MusicianProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get the main manager for the first profile
        $manager = User::where('email', 'manager@example.com')->first();

        if ($manager) {
            $profile1 = MusicianProfile::updateOrCreate(
                ['manager_id' => $manager->id],
                [
                    'uuid' => Str::uuid(),
                    'artist_name' => 'The Cool Cats',
                    'bio' => 'A cool jazz band playing smooth tunes for any occasion.',
                    'location_city' => 'New York',
                    'location_state' => 'NY',
                    'latitude' => 40.7128,
                    'longitude' => -74.0060,
                    'base_price_per_hour' => 250,
                    'is_approved' => true,
                ]
            );

            // Attach genres and event types
            $jazz = Genre::where('name', 'Jazz')->first();
            $corporate = EventType::where('name', 'Corporate Events')->first();
            $private = EventType::where('name', 'Private Parties')->first();
            $profile1->genres()->syncWithoutDetaching([$jazz->id]);
            $profile1->eventTypes()->syncWithoutDetaching([$corporate->id, $private->id]);
        }

        // Create additional managers and profiles for variety
        $manager2 = User::firstOrCreate(
            ['email' => 'manager2@example.com'],
            [
                'name' => 'Jane Doe',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );

        $profile2 = MusicianProfile::updateOrCreate(
            ['manager_id' => $manager2->id],
            [
                'uuid' => Str::uuid(),
                'artist_name' => 'Rock On',
                'bio' => 'High-energy rock band that covers classic hits and originals.',
                'location_city' => 'Los Angeles',
                'location_state' => 'CA',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'base_price_per_hour' => 300,
                'is_approved' => true,
            ]
        );

        $rock = Genre::where('name', 'Rock')->first();
        $weddings = EventType::where('name', 'Weddings')->first();
        $concerts = EventType::where('name', 'Concerts')->first();
        $profile2->genres()->syncWithoutDetaching([$rock->id]);
        $profile2->eventTypes()->syncWithoutDetaching([$weddings->id, $concerts->id]);

        $manager3 = User::firstOrCreate(
            ['email' => 'manager3@example.com'],
            [
                'name' => 'DJ Mike',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );

        $profile3 = MusicianProfile::updateOrCreate(
            ['manager_id' => $manager3->id],
            [
                'uuid' => Str::uuid(),
                'artist_name' => 'DJ Spark',
                'bio' => 'Electronic and Pop DJ for parties and festivals.',
                'location_city' => 'Miami',
                'location_state' => 'FL',
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'base_price_per_hour' => 150,
                'is_approved' => true,
            ]
        );

        $pop = Genre::where('name', 'Pop')->first();
        $electronic = Genre::where('name', 'Electronic')->first();
        $festivals = EventType::where('name', 'Festivals')->first();
        $lounge = EventType::where('name', 'Lounge DJs')->first();
        $profile3->genres()->syncWithoutDetaching([$pop->id, $electronic->id]);
        $profile3->eventTypes()->syncWithoutDetaching([$festivals->id, $lounge->id]);

        // Add 10 more bands with placeholder data
        $genres = Genre::all();
        $eventTypes = EventType::all();

        for ($i = 0; $i < 10; $i++) {
            $newManager = User::factory()->create([
                'role' => 'manager',
            ]);

            // Assign a placeholder profile picture
            $newManager->profile_picture_url = 'https://i.pravatar.cc/150?u=' . $newManager->email;
            $newManager->save();

            $newProfile = MusicianProfile::create([
                'uuid' => Str::uuid(),
                'manager_id' => $newManager->id,
                'artist_name' => $faker->words(2, true),
                'bio' => $faker->sentence(15),
                'location_city' => $faker->city,
                'location_state' => $faker->stateAbbr,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'base_price_per_hour' => $faker->numberBetween(100, 500),
                'is_approved' => true,
                'banner_image_path' => 'https://picsum.photos/seed/' . Str::slug($faker->words(2, true)) . '/1200/400',
            ]);

            // Attach random genres and event types
            $newProfile->genres()->attach($genres->random(rand(1, 3))->pluck('id')->toArray());
            $newProfile->eventTypes()->attach($eventTypes->random(rand(1, 2))->pluck('id')->toArray());
        }
    }
}
