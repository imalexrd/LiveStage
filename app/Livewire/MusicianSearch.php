<?php

namespace App\Livewire;

use App\Data\MusicianSearchFilterData;
use App\Services\MusicianProfileService;
use Livewire\Component;
use App\Models\Genre;
use App\Models\EventType;
use Livewire\Attributes\On;

class MusicianSearch extends Component
{
    public $search = '';
    public $latitude;
    public $longitude;
    public $selectedAddress;
    public $distance = 50;

    public $selectedGenres = [];
    public $selectedEventTypes = [];
    public $minPrice = 0;
    public $maxPrice = 1000;
    public $genreMatch = 'any';
    public $eventTypeMatch = 'any';
    public $searchExpanded = false;

    public $genres;
    public $eventTypes;

    public function mount()
    {
        $this->genres = Genre::all();
        $this->eventTypes = EventType::all();
    }

    #[On('locationSelected')]
    public function locationSelected($location)
    {
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];
        $this->selectedAddress = $location['address'];
    }

    public function openLocationModal()
    {
        $this->dispatch('openLocationPicker');
    }

    public function render(MusicianProfileService $profileService)
    {
        $filters = MusicianSearchFilterData::from([
            'search' => $this->search,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'distance' => $this->distance,
            'selectedGenres' => $this->selectedGenres,
            'selectedEventTypes' => $this->selectedEventTypes,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'genreMatch' => $this->genreMatch,
            'eventTypeMatch' => $this->eventTypeMatch,
        ]);

        $result = $profileService->search($filters);

        $this->searchExpanded = $result['searchExpanded'];

        return view('livewire.musician-search', [
            'musicians' => $result['musicians'],
        ]);
    }
}
