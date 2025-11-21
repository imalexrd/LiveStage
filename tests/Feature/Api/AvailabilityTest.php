<?php

namespace Tests\Feature\Api;

use App\Models\Availability;
use App\Models\MusicianProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_list_availability()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        Availability::factory()->create([
            'musician_profile_id' => $profile->id,
            'unavailable_date' => now()->addDays(5)->toDateString(),
            'reason' => 'Vacation',
        ]);

        $response = $this->actingAs($manager)->getJson('/api/v1/manager/availability');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['reason' => 'Vacation']);
    }

    public function test_manager_can_block_date()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $date = now()->addDays(10)->toDateString();

        $response = $this->actingAs($manager)->postJson('/api/v1/manager/availability', [
            'unavailable_date' => $date,
            'reason' => 'Gig',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('availabilities', [
            'musician_profile_id' => $profile->id,
            'unavailable_date' => $date,
            'reason' => 'Gig',
        ]);
    }

    public function test_manager_cannot_block_same_date_twice()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $date = now()->addDays(10)->toDateString();

        Availability::factory()->create([
            'musician_profile_id' => $profile->id,
            'unavailable_date' => $date,
        ]);

        $response = $this->actingAs($manager)->postJson('/api/v1/manager/availability', [
            'unavailable_date' => $date,
            'reason' => 'Gig',
        ]);

        $response->assertStatus(422);
    }

    public function test_manager_can_unblock_date()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $profile = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $availability = Availability::factory()->create([
            'musician_profile_id' => $profile->id,
            'unavailable_date' => now()->addDays(5)->toDateString(),
        ]);

        $response = $this->actingAs($manager)->deleteJson("/api/v1/manager/availability/{$availability->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('availabilities', ['id' => $availability->id]);
    }
}
