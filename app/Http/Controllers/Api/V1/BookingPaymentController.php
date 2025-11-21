<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;

class BookingPaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function pay(Request $request, Booking $booking)
    {
        // Ensure the user is authorized to pay for this booking (it must be their booking)
        if ($request->user()->id !== $booking->client_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->status === 'confirmed' || $booking->status === 'completed') {
             return response()->json(['error' => 'Booking is already paid or completed.'], 400);
        }

        try {
            $session = $this->stripeService->createCheckoutSession($booking);
            return response()->json(['checkout_url' => $session->url]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
