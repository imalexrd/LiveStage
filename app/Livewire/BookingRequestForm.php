<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use Livewire\Component;

class BookingRequestForm extends Component
{
    public MusicianProfile $musicianProfile;

    public $event_date;
    public $event_details;
    public $location_address;
    public $location_latitude;
    public $location_longitude;

    protected $rules = [
        'event_date' => 'required|date|after:today',
        'event_details' => 'required|string',
        'location_address' => 'nullable|string|max:255',
        'location_latitude' => 'nullable|numeric',
        'location_longitude' => 'nullable|numeric',
    ];

    public function submit()
    {
        $this->validate();

        auth()->user()->bookings()->create([
            'musician_profile_id' => $this->musicianProfile->id,
            'event_date' => $this->event_date,
            'event_details' => $this->event_details,
            'status' => 'pending',
            'location_address' => $this->location_address,
            'location_latitude' => $this->location_latitude,
            'location_longitude' => $this->location_longitude,
        ]);

        session()->flash('success', 'Booking request sent successfully!');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
