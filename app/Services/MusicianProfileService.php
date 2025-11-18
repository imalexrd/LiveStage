<?php

namespace App\Services;

use App\Data\MusicianProfileData;
use App\Models\User;
use App\Models\MusicianProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;

class MusicianProfileService
{
    public function updateProfile(User $user, MusicianProfileData $data, array $selectedGenres, array $selectedEventTypes): MusicianProfile
    {
        $city = null;
        $state = null;

        if ($data->latitude && $data->longitude) {
            $apiKey = config('services.google.maps_api_key');
            $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
                'latlng' => "{$data->latitude},{$data->longitude}",
                'key' => $apiKey,
            ]);

            if ($response->successful() && isset($response->json()['results'][0]['address_components'])) {
                $components = $response->json()['results'][0]['address_components'];
                foreach ($components as $component) {
                    if (in_array('locality', $component['types'])) {
                        $city = $component['long_name'];
                    }
                    if (in_array('administrative_area_level_1', $component['types'])) {
                        $state = $component['short_name'];
                    }
                }
            }
        }

        $profileData = $data->toArray();
        $profileData['location_city'] = $city;
        $profileData['location_state'] = $state;

        $profile = $user->musicianProfile()->updateOrCreate(
            ['manager_id' => $user->id],
            $profileData
        );

        $profile->genres()->sync($selectedGenres);
        $profile->eventTypes()->sync($selectedEventTypes);

        return $profile;
    }

    public function search(array $filters): array
    {
        $query = MusicianProfile::query()->where('is_approved', true);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('artist_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('bio', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['selectedGenres'])) {
            $query->whereHas('genres', function ($q) use ($filters) {
                $q->whereIn('genres.id', $filters['selectedGenres']);
            }, ($filters['genreMatch'] ?? 'any') === 'all' ? '=' : '>=', 1);
        }

        if (!empty($filters['selectedEventTypes'])) {
            $query->whereHas('eventTypes', function ($q) use ($filters) {
                $q->whereIn('event_types.id', $filters['selectedEventTypes']);
            }, ($filters['eventTypeMatch'] ?? 'any') === 'all' ? '=' : '>=', 1);
        }

        $query->whereBetween('base_price_per_hour', [$filters['minPrice'] ?? 0, $filters['maxPrice'] ?? 1000]);

        $searchExpanded = false;

        if (!empty($filters['latitude']) && !empty($filters['longitude'])) {
            $lat = $filters['latitude'];
            $lon = $filters['longitude'];
            $distance = $filters['distance'] ?? 50;

            $query->selectRaw(
                '*, ( 3959 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$lat, $lon, $lat]
            );

            $musiciansInRadius = (clone $query)
                ->whereRaw('( 3959 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) < ?', [$lat, $lon, $lat, $distance])
                ->orderBy('distance')
                ->get();

            if ($musiciansInRadius->isNotEmpty()) {
                $musicians = $musiciansInRadius;
            } else {
                $searchExpanded = true;
                $musicians = (clone $query)
                    ->orderBy('distance')
                    ->limit(5)
                    ->get();
            }
        } else {
            $musicians = $query->get();
        }

        return ['musicians' => $musicians, 'searchExpanded' => $searchExpanded];
    }
}
