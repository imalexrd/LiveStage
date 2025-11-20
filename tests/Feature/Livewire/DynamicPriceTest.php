<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BookingRequestForm;
use App\Models\MusicianProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DynamicPriceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_it_updates_the_price_dynamically()
    {
        $musician = MusicianProfile::first();
        $musician->update([
            'base_price_per_hour' => 100,
            'max_travel_distance_miles' => 1000,
            'travel_radius_miles' => 1000,
            'minimum_booking_notice_days' => 1,
        ]);

        // Find a weekend day in the future that is not subject to urgency fee
        $date = Carbon::now()->addDays(7);
        while (!$date->isWeekend()) {
            $date->addDay();
        }

        $appFee = 100 * (config('fees.app_fee_percentage') / 100);
        $expectedPrice = 115 + $appFee;

        Livewire::test(BookingRequestForm::class, ['musicianProfile' => $musician])
            ->set('event_date', $date->toDateString())
            ->set('location_latitude', $musician->latitude)
            ->set('location_longitude', $musician->longitude)
            ->assertSet('totalPrice', $expectedPrice)
            ->assertSet('basePrice', 100)
            ->assertSet('weekendSurcharge', 15);
    }
}
