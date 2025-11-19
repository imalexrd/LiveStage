<?php

namespace Tests\Unit\Services;

use App\Services\BookingService;
use App\Models\User;
use App\Models\MusicianProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $bookingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingService = new BookingService();
    }

    private function createManagerAndProfile(array $profileData = []): MusicianProfile
    {
        $manager = User::factory()->create(['role' => 'manager']);
        return MusicianProfile::factory()->create(array_merge(
            ['manager_id' => $manager->id],
            $profileData
        ));
    }

    /** @test */
    public function it_creates_a_booking_successfully()
    {
        $client = User::factory()->create();
        $musicianProfile = $this->createManagerAndProfile([
            'base_price_per_hour' => 150,
            'travel_radius_miles' => 20,
            'price_per_extra_mile' => 3,
            'latitude' => 34.0522, // Los Angeles
            'longitude' => -118.2437,
        ]);

        $bookingData = [
            'event_date' => now()->addMonth()->format('Y-m-d'),
            'location_address' => 'Some Address',
            'location_latitude' => 34.0522, // Same location, no travel fee
            'location_longitude' => -118.2437,
            'event_details' => 'Birthday Party',
        ];

        $priceBreakdown = $this->bookingService->calculateTotalPrice($musicianProfile, $bookingData);

        $eventDate = \Carbon\Carbon::parse($bookingData['event_date']);
        $expectedPrice = ($eventDate->isFriday() || $eventDate->isSaturday() || $eventDate->isSunday()) ? 150 * 1.15 : 150;

        $this->assertEquals(round($expectedPrice, 2), $priceBreakdown['totalPrice']);
    }

    /** @test */
    public function it_calculates_travel_fee_correctly()
    {
        $client = User::factory()->create();
        $musicianProfile = $this->createManagerAndProfile([
            'base_price_per_hour' => 200,
            'travel_radius_miles' => 50,
            'price_per_extra_mile' => 2,
            'latitude' => 40.7128, // New York
            'longitude' => -74.0060,
        ]);

        $bookingData = [
            'event_date' => now()->addMonth()->format('Y-m-d'),
            'location_address' => 'Philadelphia, PA',
            'location_latitude' => 39.9526, // Approx. 81 miles from NY
            'location_longitude' => -75.1652,
            'event_details' => 'Conference Event',
        ];

        $priceBreakdown = $this->bookingService->calculateTotalPrice($musicianProfile, $bookingData);

        $eventDate = \Carbon\Carbon::parse($bookingData['event_date']);
        $basePrice = ($eventDate->isFriday() || $eventDate->isSaturday() || $eventDate->isSunday()) ? 200 * 1.15 : 200;
        $expectedPrice = $basePrice + (81 - 50) * 2;

        $this->assertEqualsWithDelta($expectedPrice, $priceBreakdown['totalPrice'], 1.0); // Allow a tolerance of 1.0
    }

    /** @test */
    public function it_throws_validation_exception_for_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $client = User::factory()->create();
        $musicianProfile = $this->createManagerAndProfile();

        $this->bookingService->createBooking($client, $musicianProfile, ['event_date' => 'invalid-date']);
    }

    /** @test */
    public function it_throws_exception_if_location_is_beyond_max_travel_distance()
    {
        $this->expectException(ValidationException::class);

        $client = User::factory()->create();
        $musicianProfile = $this->createManagerAndProfile([
            'max_travel_distance_miles' => 100,
            'latitude' => 40.7128, // New York
            'longitude' => -74.0060,
        ]);

        $bookingData = [
            'event_date' => now()->addMonth()->format('Y-m-d'),
            'location_address' => 'Boston, MA',
            'location_latitude' => 42.3601, // > 100 miles from NY
            'location_longitude' => -71.0589,
            'event_details' => 'Wedding',
        ];

        $this->bookingService->createBooking($client, $musicianProfile, $bookingData);
    }
}
