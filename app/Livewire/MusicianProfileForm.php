<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MusicianProfileForm extends Component
{
    public $artist_name;
    public $bio;
    public $location_city;
    public $location_state;
    public $base_price_per_hour;

    public function mount()
    {
        $profile = Auth::user()->musicianProfile;

        if ($profile) {
            $this->artist_name = $profile->artist_name;
            $this->bio = $profile->bio;
            $this->location_city = $profile->location_city;
            $this->location_state = $profile->location_state;
            $this->base_price_per_hour = $profile->base_price_per_hour;
        }
    }

    public function save()
    {
        $validatedData = $this->validate([
            'artist_name' => 'required|string|max:255',
            'bio' => 'required|string',
            'location_city' => 'required|string|max:255',
            'location_state' => 'required|string|max:255',
            'base_price_per_hour' => 'required|numeric|min:0',
        ]);

        Auth::user()->musicianProfile()->updateOrCreate(
            ['manager_id' => Auth::id()],
            $validatedData
        );

        session()->flash('message', 'Profile successfully saved.');

        return redirect()->route('musician.profile');
    }

    public function render()
    {
        return view('livewire.musician-profile-form');
    }
}
