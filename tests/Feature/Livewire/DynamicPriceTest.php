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
        ]);

        // Find a weekend day in the future
        $date = Carbon::now()->addDay();
        while (!$date->isWeekend()) {
            $date->addDay();
        }

        Livewire::test(BookingRequestForm::class, ['musicianProfile' => $musician])
            ->set('event_date', $date->toDateString())
            ->set('location_latitude', $musician->latitude)
            ->set('location_longitude', $musician->longitude)
            ->assertSet('totalPrice', 115)
            ->assertSet('basePrice', 100)
            ->assertSet('weekendSurcharge', 15);
    }
}
