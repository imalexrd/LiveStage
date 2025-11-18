<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Livewire\Features\SupportValidation\Rule;

class MusicianProfileData extends Data
{
    public function __construct(
        public string $artist_name,
        public string $bio,
        public string $location_address,
        public float $base_price_per_hour,
        public float $latitude,
        public float $longitude,
        public ?string $location_city,
        public ?string $location_state,
        public ?float $travel_radius_miles,
        public ?float $max_travel_distance_miles,
        public ?float $price_per_extra_mile,
    ) {}

    public static function rules(): array
    {
        return [
            'artist_name' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string'],
            'location_address' => ['required', 'string', 'max:255'],
            'base_price_per_hour' => ['required', 'numeric', 'min:0'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'travel_radius_miles' => ['nullable', 'numeric', 'min:0'],
            'max_travel_distance_miles' => ['nullable', 'numeric', 'min:0'],
            'price_per_extra_mile' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
