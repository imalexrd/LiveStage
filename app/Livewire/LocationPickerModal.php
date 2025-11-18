<?php

namespace App\Livewire;

use Livewire\Component;

class LocationPickerModal extends Component
{
    public $show = false;
    public $address;
    public $latitude;
    public $longitude;

    protected $listeners = ['openLocationPicker'];

    public function openLocationPicker()
    {
        $this->show = true;
    }

    public function selectLocation()
    {
        $this->emit('locationSelected', [
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
