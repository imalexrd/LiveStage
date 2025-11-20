<?php

namespace App\Livewire;

use App\Data\MusicianProfileData;
use App\Services\MusicianProfileService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Genre;
use App\Models\EventType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class MusicianProfileForm extends Component
{
    public $artist_name;
    public $bio;
    public $location_address;
    public $location_city;
    public $location_state;
    public $base_price_per_hour;
    public $latitude;
    public $longitude;
    public $travel_radius_miles;
    public $max_travel_distance_miles;
    public $price_per_extra_mile;
    public $minimum_booking_notice_days;

    public $genres;
    public $eventTypes;
    public $selectedGenres = [];
    public $selectedEventTypes = [];

    public function mount()
    {
        $this->genres = Genre::all();
        $this->eventTypes = EventType::all();
        $profile = Auth::user()->musicianProfile;

        if ($profile) {
            $this->artist_name = $profile->artist_name;
            $this->bio = $profile->bio;
            $this->location_address = $profile->location_address;
            $this->location_city = $profile->location_city;
            $this->location_state = $profile->location_state;
            $this->base_price_per_hour = $profile->base_price_per_hour;
            $this->latitude = $profile->latitude;
            $this->longitude = $profile->longitude;
            $this->travel_radius_miles = $profile->travel_radius_miles;
            $this->max_travel_distance_miles = $profile->max_travel_distance_miles;
            $this->price_per_extra_mile = $profile->price_per_extra_mile;
            $this->minimum_booking_notice_days = $profile->minimum_booking_notice_days;
            $this->selectedGenres = $profile->genres->pluck('id')->toArray();
            $this->selectedEventTypes = $profile->eventTypes->pluck('id')->toArray();
        }
    }

    public function save(MusicianProfileService $profileService)
    {
        $validatedData = MusicianProfileData::from($this->all());

        $profileService->updateProfile(
            Auth::user(),
            $validatedData,
            $this->selectedGenres,
            $this->selectedEventTypes
        );

        session()->flash('message', 'Profile successfully saved.');

        return redirect()->route('musician.profile');
    }

    #[On('locationSelected')]
    public function locationSelected($location)
    {
        $this->location_address = $location['address'];
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];
    }

    public function openLocationModal()
    {
        $this->dispatch('openLocationPicker');
    }

    public function render()
    {
        return view('livewire.musician-profile-form');
    }
}
