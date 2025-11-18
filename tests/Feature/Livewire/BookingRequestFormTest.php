<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BookingRequestForm;
use App\Models\MusicianProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingRequestFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_booking_request()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musicianProfile = MusicianProfile::factory()->create([
            'manager_id' => $manager->id,
            'latitude' => 40.7128,
            'longitude' => -74.0060, // New York
            'travel_radius_miles' => 100, // Generous radius to avoid travel fees in this test
            'base_price_per_hour' => 100,
        ]);

        $this->actingAs($client);

        Livewire::test(BookingRequestForm::class, ['musicianProfile' => $musicianProfile])
            ->set('event_date', now()->addDays(10)->format('Y-m-d'))
            ->set('event_details', 'A very cool party.')
            ->set('location_address', 'Philadelphia, PA')
            ->set('location_latitude', 39.9526)
            ->set('location_longitude', -75.1652)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertDispatched('booking-success');

        $this->assertDatabaseHas('bookings', [
            'client_id' => $client->id,
            'musician_profile_id' => $musicianProfile->id,
            'status' => 'pending',
            'event_details' => 'A very cool party.',
        ]);
    }

    /** @test */
    public function it_shows_an_error_if_the_location_is_too_far()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musicianProfile = MusicianProfile::factory()->create([
            'manager_id' => $manager->id,
            'latitude' => 40.7128,
            'longitude' => -74.0060, // New York
            'max_travel_distance_miles' => 100, // Set a max travel distance
        ]);

        $this->actingAs($client);

        Livewire::test(BookingRequestForm::class, ['musicianProfile' => $musicianProfile])
            ->set('event_date', now()->addDays(10)->format('Y-m-d'))
            ->set('event_details', 'A very cool party.')
            ->set('location_address', 'Boston, MA')
            ->set('location_latitude', 42.3601)
            ->set('location_longitude', -71.0589) // Boston is > 100 miles from NY
            ->call('submit')
            ->assertHasErrors(['location_address']);
    }
}
