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

    /** @test */
    public function it_recalculates_price_correctly_when_location_is_set_before_date()
    {
        $client = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'manager']);
        $musicianProfile = MusicianProfile::factory()->create([
            'manager_id' => $manager->id,
            'latitude' => 40.7128,
            'longitude' => -74.0060, // New York
            'base_price_per_hour' => 100,
            'travel_radius_miles' => 50,
            'price_per_extra_mile' => 2,
            'minimum_booking_notice_days' => 1,
        ]);

        $this->actingAs($client);

        $testable = Livewire::test(BookingRequestForm::class, ['musicianProfile' => $musicianProfile]);

        // 1. Initial price should be the base price
        $testable->assertSet('totalPrice', 100);

        // 2. Select a location first (Philadelphia)
        $locationData = [
            'address' => 'Philadelphia, PA',
            'latitude' => 39.9526,
            'longitude' => -75.1652,
        ];
        $testable->dispatch('locationSelected', location: $locationData);

        // 3. Price should NOT have updated yet because the date is missing
        $testable->assertSet('totalPrice', 100);

        // 4. Now, select a date (a weekday to avoid surcharge)
        // This should trigger the updated hook and the price calculation
        $futureWeekday = now()->next('Wednesday')->format('Y-m-d');
        $testable->set('event_date', $futureWeekday);

        // 5. Assert the final price is correct by calculating it exactly as the service does
        $distance = $this->calculateDistance(
            $musicianProfile->latitude,
            $musicianProfile->longitude,
            $locationData['latitude'],
            $locationData['longitude']
        );
        $travelFee = ($distance - $musicianProfile->travel_radius_miles) * $musicianProfile->price_per_extra_mile;
        $appFee = 100 * (config('fees.app_fee_percentage') / 100);
        $expectedPrice = round(100 + $travelFee + $appFee, 2);

        $testable->assertSet('totalPrice', $expectedPrice);
    }

    /**
     * Helper function to calculate distance, copied from BookingService for precision.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return (float) $miles;
    }
}
