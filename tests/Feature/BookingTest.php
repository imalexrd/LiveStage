<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MusicianProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_book_a_musician()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musician = MusicianProfile::factory()->create(['manager_id' => $manager->id]);

        $this->actingAs($client);

        $response = $this->post(route('bookings.store', $musician), [
            'event_date' => '2025-12-25',
            'event_location' => 'Test Location',
            'event_details' => 'Test event details',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'client_id' => $client->id,
            'musician_profile_id' => $musician->id,
            'status' => 'pending',
        ]);
    }

    public function test_manager_can_approve_a_booking()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musician = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $booking = $musician->bookings()->create([
            'client_id' => $client->id,
            'event_date' => '2025-12-25',
            'event_location' => 'Test Location',
            'event_details' => 'Test event details',
            'status' => 'pending',
        ]);

        $this->actingAs($manager);

        $response = $this->put(route('bookings.approve', $booking));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'accepted',
        ]);
    }

    public function test_manager_can_reject_a_booking()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musician = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $booking = $musician->bookings()->create([
            'client_id' => $client->id,
            'event_date' => '2025-12-25',
            'event_location' => 'Test Location',
            'event_details' => 'Test event details',
            'status' => 'pending',
        ]);

        $this->actingAs($manager);

        $response = $this->put(route('bookings.reject', $booking));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'rejected',
        ]);
    }
}
