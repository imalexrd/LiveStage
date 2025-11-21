<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\MusicianProfile;
use App\Models\User;
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

        // Check for minimum booking notice
        $eventDate = \Carbon\Carbon::parse($validatedData['event_date']);
        if ($eventDate->isBefore(now()->addDays($musicianProfile->minimum_booking_notice_days))) {
            throw ValidationException::withMessages([
                'event_date' => 'This musician requires a minimum of ' . $musicianProfile->minimum_booking_notice_days . ' days notice.',
            ]);
        }

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

        $this->checkAvailability($musicianProfile, $validatedData['event_date']);

        $priceBreakdown = $this->calculateTotalPrice($musicianProfile, $validatedData);

        return $client->bookings()->create([
            'musician_profile_id' => $musicianProfile->id,
            'event_date' => $validatedData['event_date'],
            'location_address' => $validatedData['location_address'],
            'location_latitude' => $validatedData['location_latitude'],
            'location_longitude' => $validatedData['location_longitude'],
            'event_details' => $validatedData['event_details'],
            'status' => 'pending',
            'total_price' => $priceBreakdown['totalPrice'],
            'app_fee' => $priceBreakdown['appFee'],
            'urgency_fee' => $priceBreakdown['urgencyFee'],
        ]);
    }

    public function checkAvailability(MusicianProfile $musicianProfile, string $date): void
    {
        $isBooked = $musicianProfile->bookings()
            ->where('event_date', $date)
            ->whereIn('status', ['confirmed', 'paid', 'accepted'])
            ->exists();

        if ($isBooked) {
            throw ValidationException::withMessages([
                'event_date' => 'The musician is unavailable on this date.',
            ]);
        }

        $isBlocked = $musicianProfile->availabilities()
            ->where('unavailable_date', $date)
            ->exists();

        if ($isBlocked) {
            throw ValidationException::withMessages([
                'event_date' => 'The musician has blocked this date.',
            ]);
        }
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

    public function calculateTotalPrice(MusicianProfile $musicianProfile, array $data): array
    {
        $distance = $this->calculateDistance(
            $musicianProfile->latitude,
            $musicianProfile->longitude,
            $data['location_latitude'],
            $data['location_longitude']
        );

        $travelFee = 0;
        if ($distance > $musicianProfile->travel_radius_miles) {
            $extraMiles = $distance - $musicianProfile->travel_radius_miles;
            $travelFee = $extraMiles * $musicianProfile->price_per_extra_mile;
        }

        $basePrice = $musicianProfile->base_price_per_hour;
        $weekendSurcharge = 0;
        if (isset($data['event_date'])) {
            $eventDate = \Carbon\Carbon::parse($data['event_date']);
            if ($eventDate->isFriday() || $eventDate->isSaturday() || $eventDate->isSunday()) {
                $weekendSurcharge = $basePrice * 0.15;
            }
        }

        $urgencyFee = 0;
        if (isset($data['event_date'])) {
            $eventDate = \Carbon\Carbon::parse($data['event_date']);
            if ($eventDate->isBefore(now()->addDays(config('fees.urgency_threshold_days')))) {
                $urgencyFee = $basePrice * (config('fees.urgency_fee_percentage') / 100);
            }
        }

        $appFee = $basePrice * (config('fees.app_fee_percentage') / 100);

        $totalPrice = $basePrice + $weekendSurcharge + $travelFee + $urgencyFee + $appFee;

        return [
            'basePrice' => round($basePrice, 2),
            'weekendSurcharge' => round($weekendSurcharge, 2),
            'travelFee' => round($travelFee, 2),
            'urgencyFee' => round($urgencyFee, 2),
            'totalPrice' => round($totalPrice, 2),
            'appFee' => round($appFee, 2),
        ];
    }
}
