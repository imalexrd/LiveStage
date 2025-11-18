<?php

namespace App\Livewire;

use App\Models\MusicianProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Genre;
use App\Models\EventType;
use Illuminate\Validation\Rule;

class MusicianProfileForm extends Component
{
    public $artist_name;
    public $bio;
    public $location_city;
    public $location_state;
    public $base_price_per_hour;
    public $latitude;
    public $longitude;
    public $travel_radius_miles;
    public $max_travel_distance_miles;
    public $price_per_extra_mile;

    public $genres;
    public $eventTypes;
    public $selectedGenres = [];
    public $selectedEventTypes = [];

    public $supportedCities = [
        'New York',
        'Los Angeles',
        'Miami',
    ];

    public function mount()
    {
        $this->genres = Genre::all();
        $this->eventTypes = EventType::all();
        $profile = Auth::user()->musicianProfile;

        if ($profile) {
            $this->artist_name = $profile->artist_name;
            $this->bio = $profile->bio;
            $this->location_city = $profile->location_city;
            $this->location_state = $profile->location_state;
            $this->base_price_per_hour = $profile->base_price_per_hour;
            $this->latitude = $profile->latitude;
            $this->longitude = $profile->longitude;
            $this->travel_radius_miles = $profile->travel_radius_miles;
            $this->max_travel_distance_miles = $profile->max_travel_distance_miles;
            $this->price_per_extra_mile = $profile->price_per_extra_mile;
            $this->selectedGenres = $profile->genres->pluck('id')->toArray();
            $this->selectedEventTypes = $profile->eventTypes->pluck('id')->toArray();
        }
    }

    public function save()
    {
        $validatedData = $this->validate([
            'artist_name' => 'required|string|max:255',
            'bio' => 'required|string',
            'location_city' => ['required', Rule::in($this->supportedCities)],
            'location_state' => 'required|string|max:255',
            'base_price_per_hour' => 'required|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'travel_radius_miles' => 'nullable|numeric|min:0',
            'max_travel_distance_miles' => 'nullable|numeric|min:0',
            'price_per_extra_mile' => 'nullable|numeric|min:0',
        ]);

        $profile = Auth::user()->musicianProfile()->updateOrCreate(
            ['manager_id' => Auth::id()],
            $validatedData
        );

        $profile->genres()->sync($this->selectedGenres);
        $profile->eventTypes()->sync($this->selectedEventTypes);

        session()->flash('message', 'Profile successfully saved.');

        return redirect()->route('musician.profile');
    }

    public function openLocationModal()
    {
        $this->emit('openLocationPicker');
    }

    public function render()
    {
        return view('livewire.musician-profile-form');
    }
}
