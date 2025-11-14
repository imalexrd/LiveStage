<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MusicianProfile;
use App\Models\Genre;
use App\Models\EventType;

class MusicianSearch extends Component
{
    public $search = '';
    public $location = '';
    public $radius = 10;
    public $selectedGenres = [];
    public $selectedEventTypes = [];
    public $minPrice = 0;
    public $maxPrice = 1000;

    public $genres;
    public $eventTypes;

    public function mount()
    {
        $this->genres = Genre::all();
        $this->eventTypes = EventType::all();
    }

    public function render()
    {
        $query = MusicianProfile::query()->where('is_approved', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('artist_name', 'like', '%' . $this->search . '%')
                  ->orWhere('bio', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->selectedGenres)) {
            $query->whereHas('genres', function ($q) {
                $q->whereIn('genres.id', $this->selectedGenres);
            });
        }

        if (!empty($this->selectedEventTypes)) {
            $query->whereHas('eventTypes', function ($q) {
                $q->whereIn('event_types.id', $this->selectedEventTypes);
            });
        }

        $query->whereBetween('base_price_per_hour', [$this->minPrice, $this->maxPrice]);

        // Placeholder for geospatial search
        if ($this->location) {
            // TODO: Implement geospatial search logic here.
            // For now, we'll just filter by city.
            $query->where('location_city', 'like', '%' . $this->location . '%');
        }

        $musicians = $query->get();

        return view('livewire.musician-search', [
            'musicians' => $musicians,
        ]);
    }
}
