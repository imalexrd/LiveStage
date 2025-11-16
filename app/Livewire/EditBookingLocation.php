<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;

class EditBookingLocation extends Component
{
    public Booking $booking;
    public $showModal = false;

    public $location_address;
    public $location_latitude;
    public $location_longitude;

    protected $rules = [
        'location_address' => 'nullable|string|max:255',
        'location_latitude' => 'nullable|numeric',
        'location_longitude' => 'nullable|numeric',
    ];

    public function mount()
    {
        $this->location_address = $this->booking->location_address;
        $this->location_latitude = $this->booking->location_latitude;
        $this->location_longitude = $this->booking->location_longitude;
    }

    public function save()
    {
        $this->validate();

        $this->booking->update([
            'location_address' => $this->location_address,
            'location_latitude' => $this->location_latitude,
            'location_longitude' => $this->location_longitude,
        ]);

        $this->showModal = false;
        $this->dispatch('locationUpdated');
    }

    public function render()
    {
        return view('livewire.edit-booking-location');
    }
}
