<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class LocationPickerModal extends Component
{
    public $show = false;

    #[On('openLocationPicker')]
    public function openLocationPicker()
    {
        $this->show = true;
    }

    public function selectLocation($location)
    {
        $this->dispatch('locationSelected', $location);
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.location-picker-modal');
    }
}
