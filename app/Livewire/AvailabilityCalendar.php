<?php

namespace App\Livewire;

use App\Services\AvailabilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\On;

class AvailabilityCalendar extends Component
{
    public function render()
    {
        return view('livewire.availability-calendar');
    }

    /**
     * Get events for the calendar.
     *
     * @param AvailabilityService $service
     * @return array
     */
    public function getEvents(AvailabilityService $service)
    {
        $profile = Auth::user()->musicianProfile;
        if (!$profile) {
            return [];
        }
        return $service->getCalendarEvents($profile);
    }

    /**
     * Block a specific date.
     *
     * @param AvailabilityService $service
     * @param string $date
     * @param string|null $reason
     */
    public function blockDate(AvailabilityService $service, $date, $reason)
    {
        $profile = Auth::user()->musicianProfile;
        if (!$profile) {
            return;
        }

        try {
            $service->blockDate($profile, $date, $reason);
            $this->dispatch('calendar-updated', events: $this->getEvents($service));
            session()->flash('message', 'Date blocked successfully.');
        } catch (ValidationException $e) {
            session()->flash('error', current($e->errors())[0]);
        }
    }

    /**
     * Unblock a specific availability ID.
     *
     * @param AvailabilityService $service
     * @param int $id
     */
    public function unblockDate(AvailabilityService $service, $id)
    {
        $profile = Auth::user()->musicianProfile;
        if (!$profile) {
            return;
        }

        try {
            $service->unblockDate($profile, $id);
            $this->dispatch('calendar-updated', events: $this->getEvents($service));
            session()->flash('message', 'Date unblocked successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Could not unblock date.');
        }
    }
}
