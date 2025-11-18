<?php

namespace Tests\Unit\Services;

use App\Data\MusicianProfileData;
use App\Models\EventType;
use App\Models\Genre;
use App\Models\MusicianProfile;
use App\Models\User;
use App\Services\MusicianProfileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MusicianProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MusicianProfileService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MusicianProfileService::class);
    }

    public function test_it_updates_a_musician_profile()
    {
        $user = User::factory()->create(['role' => 'manager']);
        $genre = Genre::factory()->create();
        $eventType = EventType::factory()->create();

        Http::fake([
            'https://maps.googleapis.com/*' => Http::response([
                'results' => [
                    [
                        'address_components' => [
                            ['long_name' => 'Anytown', 'types' => ['locality']],
                            ['short_name' => 'AS', 'types' => ['administrative_area_level_1']],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $data = new MusicianProfileData(
            artist_name: 'The Testers',
            bio: 'A test band.',
            location_address: '123 Test St',
            base_price_per_hour: 150.00,
            latitude: 34.0522,
            longitude: -118.2437,
            location_city: null,
            location_state: null,
            travel_radius_miles: 50,
            max_travel_distance_miles: 200,
            price_per_extra_mile: 2.50
        );

        $this->service->updateProfile($user, $data, [$genre->id], [$eventType->id]);

        $this->assertDatabaseHas('musician_profiles', [
            'manager_id' => $user->id,
            'artist_name' => 'The Testers',
            'location_city' => 'Anytown',
            'location_state' => 'AS',
        ]);
        $this->assertDatabaseHas('genre_musician_profile', [
            'genre_id' => $genre->id,
        ]);
        $this->assertDatabaseHas('event_type_musician_profile', [
            'event_type_id' => $eventType->id,
        ]);
    }

    public function test_it_searches_for_musicians()
    {
        MusicianProfile::factory()->create(['artist_name' => 'The Rockers', 'is_approved' => true]);
        MusicianProfile::factory()->create(['artist_name' => 'The Jazz Cats', 'is_approved' => true]);

        $filters = ['search' => 'Rock'];
        $result = $this->service->search($filters);

        $this->assertCount(1, $result['musicians']);
        $this->assertEquals('The Rockers', $result['musicians']->first()->artist_name);
    }
}
