<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MusicianProfile;
use App\Models\Genre;
use App\Models\EventType;
use Illuminate\Validation\Rule;

class MusicianSearch extends Component
{
    public $search = '';
    public $latitude;
    public $longitude;
    public $selectedAddress;
    public $distance = 50;

    protected $listeners = ['locationSelected'];
    public $selectedGenres = [];
    public $selectedEventTypes = [];
    public $minPrice = 0;
    public $maxPrice = 1000;
    public $genreMatch = 'any';
    public $eventTypeMatch = 'any';

    public $genres;
    public $eventTypes;

    public function mount()
    {
        $this->genres = Genre::all();
        $this->eventTypes = EventType::all();
    }

    public function locationSelected($location)
    {
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];
        $this->selectedAddress = $location['address'];
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

        if ($this->latitude && $this->longitude) {
            $query->selectRaw(
                '*, ( 3959 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$this->latitude, $this->longitude, $this->latitude]
            )
            ->having('distance', '<', $this->distance)
            ->orderBy('distance');
        }

        if (!empty($this->selectedGenres)) {
            $query->whereHas('genres', function ($q) {
                $q->whereIn('genres.id', $this->selectedGenres);
            }, $this->genreMatch === 'all' ? '=' : '>=', 1);
        }

        if (!empty($this->selectedEventTypes)) {
            $query->whereHas('eventTypes', function ($q) {
                $q->whereIn('event_types.id', $this->selectedEventTypes);
            }, $this->eventTypeMatch === 'all' ? '=' : '>=', 1);
        }

        $query->whereBetween('base_price_per_hour', [$this->minPrice, $this->maxPrice]);

        $musicians = $query->get();

        return view('livewire.musician-search', [
            'musicians' => $musicians,
        ]);
    }
}
