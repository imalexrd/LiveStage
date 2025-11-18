<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class LocationPickerModal extends Component
{
    public $show = false;
    public $address;
    public $latitude;
    public $longitude;

    #[On('openLocationPicker')]
    public function openLocationPicker()
    {
        $this->show = true;
    }

    public function selectLocation()
    {
        $this->dispatch('locationSelected', [
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.location-picker-modal');
    }
}
