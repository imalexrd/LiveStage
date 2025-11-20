<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Booking;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCheckoutSession(Booking $booking)
    {
        $musician = $booking->musicianProfile;

        if (!$musician->stripe_connect_id) {
            throw new \Exception('Musician is not connected to Stripe.');
        }

        $totalPriceCents = (int) round($booking->total_price * 100);
        $appFeeCents = (int) round($booking->app_fee * 100);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Booking: ' . $musician->artist_name,
                        'description' => substr($booking->event_details, 0, 500),
                    ],
                    'unit_amount' => $totalPriceCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // We redirect to a generic success page or the booking page.
            // The webhook will update the status.
            // We add a query param so the UI can show a "Processing Payment" message.
            'success_url' => route('bookings.show', $booking->id) . '?payment_success=true',
            'cancel_url' => route('musician.profile.show', $musician->uuid),
            'payment_intent_data' => [
                'application_fee_amount' => $appFeeCents,
                'transfer_data' => [
                    'destination' => $musician->stripe_connect_id,
                ],
            ],
            'metadata' => [
                'booking_id' => $booking->id,
            ],
        ]);

        return $session;
    }
}
