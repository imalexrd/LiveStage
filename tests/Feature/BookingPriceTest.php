<?php

namespace Tests\Feature;

use App\Models\MusicianProfile;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPriceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_it_calculates_the_price_correctly_for_a_weekday_booking()
    {
        $client = User::factory()->create();
        $musician = MusicianProfile::first();
        $musician->update([
            'base_price_per_hour' => 100,
            'max_travel_distance_miles' => 1000,
            'travel_radius_miles' => 1000,
        ]);

        // Find a weekday in the future
        $date = Carbon::now()->addDay();
        while ($date->isWeekend()) {
            $date->addDay();
        }

        $bookingData = [
            'event_date' => $date->toDateString(),
            'location_address' => '123 Main St',
            'location_latitude' => $musician->latitude,
            'location_longitude' => $musician->longitude,
            'event_details' => 'A test event.',
        ];

        $bookingService = new BookingService();
        $priceBreakdown = $bookingService->calculateTotalPrice($musician, $bookingData);

        $this->assertEquals(100, $priceBreakdown['totalPrice']);
        $this->assertEquals(0, $priceBreakdown['weekendSurcharge']);
    }

    public function test_it_applies_a_surcharge_for_a_weekend_booking()
    {
        $client = User::factory()->create();
        $musician = MusicianProfile::first();
        $musician->update([
            'base_price_per_hour' => 100,
            'max_travel_distance_miles' => 1000,
            'travel_radius_miles' => 1000,
        ]);

        // Find a weekend day in the future
        $date = Carbon::now()->addDay();
        while (!$date->isWeekend()) {
            $date->addDay();
        }

        $bookingData = [
            'event_date' => $date->toDateString(),
            'location_address' => '123 Main St',
            'location_latitude' => $musician->latitude,
            'location_longitude' => $musician->longitude,
            'event_details' => 'A test event.',
        ];

        $bookingService = new BookingService();
        $priceBreakdown = $bookingService->calculateTotalPrice($musician, $bookingData);

        $this->assertEquals(115, $priceBreakdown['totalPrice']);
        $this->assertEquals(15, $priceBreakdown['weekendSurcharge']);
    }

    public function test_it_applies_a_surcharge_for_a_friday_booking()
    {
        $client = User::factory()->create();
        $musician = MusicianProfile::first();
        $musician->update([
            'base_price_per_hour' => 100,
            'max_travel_distance_miles' => 1000,
            'travel_radius_miles' => 1000,
        ]);

        // Find a Friday in the future
        $date = Carbon::now()->addDay();
        while (!$date->isFriday()) {
            $date->addDay();
        }

        $bookingData = [
            'event_date' => $date->toDateString(),
            'location_address' => '123 Main St',
            'location_latitude' => $musician->latitude,
            'location_longitude' => $musician->longitude,
            'event_details' => 'A test event.',
        ];

        $bookingService = new BookingService();
        $priceBreakdown = $bookingService->calculateTotalPrice($musician, $bookingData);

        $this->assertEquals(115, $priceBreakdown['totalPrice']);
        $this->assertEquals(15, $priceBreakdown['weekendSurcharge']);
    }
}
