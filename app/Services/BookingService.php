<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\MusicianProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a new booking request.
     *
     * @param User $client
     * @param MusicianProfile $musicianProfile
     * @param array $data
     * @return Booking
     * @throws ValidationException
     */
    public function createBooking(User $client, MusicianProfile $musicianProfile, array $data): Booking
    {
        $validator = Validator::make($data, [
            'event_date' => 'required|date|after:today',
            'location_address' => 'required|string|max:255',
            'location_latitude' => 'required|numeric',
            'location_longitude' => 'required|numeric',
            'event_details' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();

        $distance = $this->calculateDistance(
            $musicianProfile->latitude,
            $musicianProfile->longitude,
            $validatedData['location_latitude'],
            $validatedData['location_longitude']
        );

        if ($musicianProfile->max_travel_distance_miles && $distance > $musicianProfile->max_travel_distance_miles) {
            throw ValidationException::withMessages([
                'location_address' => 'This musician does not travel to this location.',
            ]);
        }

        $travelFee = 0;
        if ($distance > $musicianProfile->travel_radius_miles) {
            $extraMiles = $distance - $musicianProfile->travel_radius_miles;
            $travelFee = $extraMiles * $musicianProfile->price_per_extra_mile;
        }

        $basePrice = $musicianProfile->base_price_per_hour;
        $eventDate = Carbon::parse($validatedData['event_date']);
        if ($eventDate->isFriday() || $eventDate->isSaturday() || $eventDate->isSunday()) {
            $basePrice *= 1.15;
        }

        $totalPrice = $basePrice + $travelFee;

        return $client->bookings()->create([
            'musician_profile_id' => $musicianProfile->id,
            'event_date' => $validatedData['event_date'],
            'location_address' => $validatedData['location_address'],
            'location_latitude' => $validatedData['location_latitude'],
            'location_longitude' => $validatedData['location_longitude'],
            'event_details' => $validatedData['event_details'],
            'status' => 'pending',
            'total_price' => round($totalPrice, 2),
        ]);
    }

    /**
     * Calculate the distance between two geo-points in miles.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return (float) $miles;
    }
}
