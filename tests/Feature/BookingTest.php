<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MusicianProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Booking;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_book_a_musician()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musician = MusicianProfile::factory()->create(['manager_id' => $manager->id]);

        $this->actingAs($client);

        $bookingData = [
            'event_date' => '2025-12-25',
            'location_address' => '123 Main St, Anytown, USA',
            'location_latitude' => 40.7128,
            'location_longitude' => -74.0060,
            'event_details' => 'Test event details',
        ];

        $response = $this->post(route('bookings.store', $musician), $bookingData);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'client_id' => $client->id,
            'musician_profile_id' => $musician->id,
            'status' => 'pending',
            'location_address' => '123 Main St, Anytown, USA',
        ]);
    }

    public function test_manager_can_approve_a_booking()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musician = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $booking = Booking::factory()->create([
            'client_id' => $client->id,
            'musician_profile_id' => $musician->id,
            'status' => 'pending',
        ]);

        $this->actingAs($manager);

        $response = $this->put(route('bookings.approve', $booking));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_manager_can_reject_a_booking()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musician = MusicianProfile::factory()->create(['manager_id' => $manager->id]);
        $booking = Booking::factory()->create([
            'client_id' => $client->id,
            'musician_profile_id' => $musician->id,
            'status' => 'pending',
        ]);

        $this->actingAs($manager);

        $response = $this->put(route('bookings.reject', $booking));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    }
}
