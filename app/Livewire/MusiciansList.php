<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use Livewire\Component;

class MusiciansList extends Component
{
    public function render()
    {
        $musicians = MusicianProfile::where('is_approved', true)->get();

        return view('livewire.musicians-list', [
            'musicians' => $musicians,
        ]);
    }
}
