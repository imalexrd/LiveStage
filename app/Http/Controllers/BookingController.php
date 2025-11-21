<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MusicianProfile;
use App\Services\BookingService;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function pay(Booking $booking, StripePaymentService $paymentService, BookingService $bookingService)
    {
        if (Auth::id() !== $booking->client_id) {
            abort(403);
        }

        try {
            // Re-check availability
            $bookingService->checkAvailability($booking->musicianProfile, $booking->event_date);

            $session = $paymentService->createCheckoutSession($booking);
            return redirect($session->url);
        } catch (ValidationException $e) {
            return back()->with('error', 'Booking unavailable: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request, MusicianProfile $musicianProfile, BookingService $bookingService)
    {
        try {
            $bookingService->createBooking(Auth::user(), $musicianProfile, $request->all());

            return back()->with('success', 'Booking request sent successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function show(Request $request, Booking $booking, StripePaymentService $paymentService)
    {
        $this->authorize('view', $booking);

        if ($request->has('session_id') && $booking->status === 'pending') {
            try {
                $updatedBooking = $paymentService->handlePaymentSuccess($request->session_id);
                if ($updatedBooking) {
                    session()->flash('success', 'Payment confirmed successfully!');
                    $booking->refresh();
                }
            } catch (\Exception $e) {
                // Log error but show booking
            }
        }

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
