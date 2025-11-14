<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class BookingList extends Component
{
    use AuthorizesRequests;
{
    public function render()
    {
        $bookings = auth()->user()->role === 'manager'
            ? auth()->user()->musicianProfile->bookings()->latest()->paginate(10)
            : auth()->user()->bookings()->latest()->paginate(10);

        return view('livewire.booking-list', [
            'bookings' => $bookings,
        ]);
    }

    public function approveBooking($bookingId)
    {
        $booking = auth()->user()->musicianProfile->bookings()->findOrFail($bookingId);
        $this->authorize('update', $booking);
        $booking->update(['status' => 'accepted']);
        session()->flash('success', 'Booking approved successfully!');
    }

    public function rejectBooking($bookingId)
    {
        $booking = auth()->user()->musicianProfile->bookings()->findOrFail($bookingId);
        $this->authorize('update', $booking);
        $booking->update(['status' => 'rejected']);
        session()->flash('success', 'Booking rejected successfully!');
    }
}
