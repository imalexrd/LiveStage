<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use Livewire\Component;
use Livewire\Attributes\On;

class BookingRequestForm extends Component
{
    public MusicianProfile $musicianProfile;

    public $event_date;
    public $location_address;
    public $location_latitude;
    public $location_longitude;
    public $event_details;
    public $travelFee = 0;
    public $totalPrice = 0;

    protected $rules = [
        'event_date' => 'required|date|after:today',
        'location_address' => 'required|string|max:255',
        'location_latitude' => 'required|numeric',
        'location_longitude' => 'required|numeric',
        'event_details' => 'required|string',
    ];

    public function mount()
    {
        $this->totalPrice = $this->musicianProfile->base_price_per_hour;
    }

    #[On('locationSelected')]
    public function locationSelected($location)
    {
        $this->location_address = $location['address'];
        $this->location_latitude = $location['latitude'];
        $this->location_longitude = $location['longitude'];

        $distance = $this->calculateDistance(
            $this->musicianProfile->latitude,
            $this->musicianProfile->longitude,
            $this->location_latitude,
            $this->location_longitude
        );

        if ($this->musicianProfile->max_travel_distance_miles && $distance > $this->musicianProfile->max_travel_distance_miles) {
            $this->addError('location_address', 'This musician does not travel to this location.');
            return;
        }

        if ($distance > $this->musicianProfile->travel_radius_miles) {
            $extraMiles = $distance - $this->musicianProfile->travel_radius_miles;
            $this->travelFee = $extraMiles * $this->musicianProfile->price_per_extra_mile;
        } else {
            $this->travelFee = 0;
        }

        $this->totalPrice = $this->musicianProfile->base_price_per_hour + $this->travelFee;
    }

    public function submit()
    {
        $this->validate();

        auth()->user()->bookings()->create([
            'musician_profile_id' => $this->musicianProfile->id,
            'event_date' => $this->event_date,
            'location_address' => $this->location_address,
            'location_latitude' => $this->location_latitude,
            'location_longitude' => $this->location_longitude,
            'event_details' => $this->event_details,
            'status' => 'pending',
            'total_price' => $this->totalPrice,
        ]);

        session()->flash('success', 'Booking request sent successfully!');

        $this->reset();
    }

    public function openLocationModal()
    {
        $this->dispatch('openLocationPicker');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles;
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
