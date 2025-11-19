<?php

namespace Tests\Feature\Api\V1;

use App\Models\EventType;
use App\Models\Genre;
use App\Models\MusicianProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MusicianProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_show_a_musician_profile()
    {
        $this->seed();

        $musicianProfile = MusicianProfile::first();

        $response = $this->getJson('/api/v1/musicians/' . $musicianProfile->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $musicianProfile->id,
                    'artist_name' => $musicianProfile->artist_name,
                ],
            ]);
    }

    public function test_a_manager_can_create_a_profile()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $profileData = [
            'artist_name' => 'The Testers',
            'bio' => 'A test band.',
            'base_price_per_hour' => 100.00,
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ];

        $response = $this->actingAs($manager, 'sanctum')->postJson('/api/v1/musicians', $profileData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'artist_name' => 'The Testers',
                ],
            ]);

        $this->assertDatabaseHas('musician_profiles', ['artist_name' => 'The Testers']);
    }

    public function test_a_manager_cannot_create_more_than_one_profile()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        MusicianProfile::factory()->create(['manager_id' => $manager->id]);

        $profileData = [
            'artist_name' => 'The Duplicates',
            'bio' => 'Another test band.',
            'base_price_per_hour' => 150.00,
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ];

        $response = $this->actingAs($manager, 'sanctum')->postJson('/api/v1/musicians', $profileData);

        $response->assertStatus(403);
    }

    public function test_the_profile_owner_can_update_their_profile()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);

        $genres = Genre::factory()->count(3)->create();
        $eventTypes = EventType::factory()->count(2)->create();

        $updateData = [
            'artist_name' => 'Updated Name',
            'bio' => 'Updated Bio',
            'base_price_per_hour' => 200.00,
            'latitude' => 35.0522,
            'longitude' => -119.2437,
            'selectedGenres' => $genres->pluck('id')->toArray(),
            'selectedEventTypes' => $eventTypes->pluck('id')->toArray(),
        ];

        $response = $this->actingAs($manager, 'sanctum')->putJson('/api/v1/musicians/' . $profile->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'artist_name' => 'Updated Name',
                ],
            ]);

        $this->assertDatabaseHas('musician_profiles', ['id' => $profile->id, 'artist_name' => 'Updated Name']);
        $this->assertCount(3, $profile->refresh()->genres);
        $this->assertCount(2, $profile->refresh()->eventTypes);
    }

    public function test_an_unauthorized_user_cannot_update_a_profile()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $unauthorizedUser = User::factory()->create();

        $updateData = ['artist_name' => 'Unauthorized Update'];

        $response = $this->actingAs($unauthorizedUser, 'sanctum')->putJson('/api/v1/musicians/' . $profile->id, $updateData);

        $response->assertStatus(403);
    }

    public function test_the_profile_owner_can_delete_their_profile()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);

        $response = $this->actingAs($manager, 'sanctum')->deleteJson('/api/v1/musicians/' . $profile->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('musician_profiles', ['id' => $profile->id]);
    }

    public function test_an_unauthorized_user_cannot_delete_a_profile()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $unauthorizedUser = User::factory()->create();

        $response = $this->actingAs($unauthorizedUser, 'sanctum')->deleteJson('/api/v1/musicians/' . $profile->id);

        $response->assertStatus(403);
    }
}
