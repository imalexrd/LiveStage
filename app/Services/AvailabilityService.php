<?php

namespace App\Services;

use App\Models\MusicianProfile;
use App\Models\Booking;
use App\Models\Availability;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Get all calendar events (Bookings + Availabilities) for a profile.
     *
     * @param MusicianProfile $profile
     * @return array
     */
    public function getCalendarEvents(MusicianProfile $profile): array
    {
        $events = [];

        // 1. Get Confirmed Bookings (Red, Read-only)
        // We exclude cancelled/pending if we only want to show confirmed blocks.
        // However, pending might be relevant? The prompt says "Confirmed Bookings".
        // Let's stick to 'confirmed', 'paid', 'completed'.
        $bookings = $profile->bookings()
            ->whereIn('status', ['confirmed', 'paid', 'completed'])
            ->get();

        foreach ($bookings as $booking) {
            $events[] = [
                'id' => 'booking_' . $booking->id,
                'title' => 'Booked', // Or "Event"
                'start' => $booking->event_date->format('Y-m-d'),
                'allDay' => true,
                'color' => '#ef4444', // red-500
                'extendedProps' => [
                    'type' => 'booking',
                    'booking_id' => $booking->id,
                    'editable' => false,
                ],
            ];
        }

        // 2. Get Blocked Dates (Gray, Editable)
        $availabilities = $profile->availabilities()->get();

        foreach ($availabilities as $availability) {
            $events[] = [
                'id' => 'availability_' . $availability->id,
                'title' => $availability->reason ?? 'Unavailable',
                'start' => $availability->unavailable_date, // It's a string or date cast
                'allDay' => true,
                'color' => '#9ca3af', // gray-400
                'extendedProps' => [
                    'type' => 'availability',
                    'availability_id' => $availability->id,
                    'editable' => true,
                ],
            ];
        }

        return $events;
    }

    /**
     * Block a date.
     *
     * @param MusicianProfile $profile
     * @param string $date
     * @param string|null $reason
     * @return Availability
     * @throws ValidationException
     */
    public function blockDate(MusicianProfile $profile, string $date, ?string $reason = null): Availability
    {
        // Check if there's already a confirmed booking
        $hasBooking = $profile->bookings()
            ->where('event_date', $date)
            ->whereIn('status', ['confirmed', 'paid', 'completed'])
            ->exists();

        if ($hasBooking) {
            throw ValidationException::withMessages([
                'unavailable_date' => 'This date is already booked.',
            ]);
        }

        // Check if already blocked
        $isBlocked = $profile->availabilities()
            ->where('unavailable_date', $date)
            ->exists();

        if ($isBlocked) {
            throw ValidationException::withMessages([
                'unavailable_date' => 'This date is already blocked.',
            ]);
        }

        return $profile->availabilities()->create([
            'unavailable_date' => $date,
            'reason' => $reason,
        ]);
    }

    /**
     * Unblock a date.
     *
     * @param MusicianProfile $profile
     * @param int $availabilityId
     * @return void
     */
    public function unblockDate(MusicianProfile $profile, int $availabilityId): void
    {
        $availability = $profile->availabilities()->findOrFail($availabilityId);
        $availability->delete();
    }

    /**
     * Get future availabilities (blocks) for API.
     *
     * @param MusicianProfile $profile
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFutureAvailabilities(MusicianProfile $profile)
    {
        return $profile->availabilities()
            ->where('unavailable_date', '>=', now()->toDateString())
            ->orderBy('unavailable_date')
            ->get();
    }
}
