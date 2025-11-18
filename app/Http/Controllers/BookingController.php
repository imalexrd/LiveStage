<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MusicianProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request, MusicianProfile $musicianProfile)
    {
        $request->validate([
            'event_date' => 'required|date|after:today',
            'location_address' => 'required|string|max:255',
            'location_latitude' => 'required|numeric',
            'location_longitude' => 'required|numeric',
            'event_details' => 'required|string',
        ]);

        $booking = new Booking();
        $booking->client_id = Auth::id();
        $booking->musician_profile_id = $musicianProfile->id;
        $booking->event_date = $request->event_date;
        $booking->location_address = $request->location_address;
        $booking->location_latitude = $request->location_latitude;
        $booking->location_longitude = $request->location_longitude;
        $booking->event_details = $request->event_details;
        $booking->status = 'pending';
        $booking->save();

        // TODO: Add notification to manager

        return back()->with('success', 'Booking request sent successfully!');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        $this->authorize('update', $booking);
        $booking->status = 'confirmed';
        $booking->save();

        // TODO: Add notification to client

        return back()->with('success', 'Booking request accepted!');
    }

    public function reject(Booking $booking)
    {
        $this->authorize('update', $booking);
        $booking->status = 'cancelled';
        $booking->save();

        // TODO: Add notification to client

        return back()->with('success', 'Booking request rejected!');
    }
}
