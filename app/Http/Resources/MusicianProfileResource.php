<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MusicianProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'artist_name' => $this->artist_name,
            'bio' => $this->bio,
            'location_city' => $this->location_city,
            'location_state' => $this->location_state,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'base_price_per_hour' => $this->base_price_per_hour,
            'distance' => $this->when(isset($this->distance), $this->distance),
            'genres' => $this->whenLoaded('genres', fn () => $this->genres->pluck('name')),
            'event_types' => $this->whenLoaded('eventTypes', fn () => $this->eventTypes->pluck('name')),
        ];
    }
}
