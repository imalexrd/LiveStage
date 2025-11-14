<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use Livewire\Component;

class BookingRequestForm extends Component
{
    public MusicianProfile $musicianProfile;

    public $event_date;
    public $event_location;
    public $event_details;

    protected $rules = [
        'event_date' => 'required|date|after:today',
        'event_location' => 'required|string|max:255',
        'event_details' => 'required|string',
    ];

    public function submit()
    {
        $this->validate();

        auth()->user()->bookings()->create([
            'musician_profile_id' => $this->musicianProfile->id,
            'event_date' => $this->event_date,
            'event_location' => $this->event_location,
            'event_details' => $this->event_details,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Booking request sent successfully!');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
