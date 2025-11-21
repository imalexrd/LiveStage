<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Account;
use Stripe\AccountLink;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createAccountLink(User $user): string
    {
        if ($user->role !== 'manager') {
            throw new \Exception('Only managers can connect Stripe accounts.');
        }

        $profile = $user->musicianProfile;

        if (!$profile) {
            throw new \Exception('You must create a musician profile first.');
        }

        if (!$profile->stripe_connect_id) {
            $account = Account::create([
                'type' => 'standard',
                'email' => $user->email,
            ]);

            $profile->stripe_connect_id = $account->id;
            $profile->save();
        }

        $accountLink = AccountLink::create([
            'account' => $profile->stripe_connect_id,
            'refresh_url' => route('stripe.connect'),
            'return_url' => route('stripe.callback'),
            'type' => 'account_onboarding',
        ]);

        return $accountLink->url;
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
            // We append session_id for manual verification on return
            'success_url' => route('bookings.show', $booking->id) . '?payment_success=true&session_id={CHECKOUT_SESSION_ID}',
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

    public function handlePaymentSuccess(string $sessionId)
    {
        try {
            $session = Session::retrieve($sessionId);
        } catch (\Exception $e) {
            Log::error('Stripe Payment: Unable to retrieve session', ['session_id' => $sessionId, 'error' => $e->getMessage()]);
            return null;
        }

        if ($session->payment_status !== 'paid') {
            return null;
        }

        $bookingId = $session->metadata->booking_id ?? null;
        if (!$bookingId) {
            Log::error('Stripe Payment: Booking ID not found in metadata', ['session_id' => $sessionId]);
            return null;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
             Log::error('Stripe Payment: Booking not found', ['booking_id' => $bookingId]);
             return null;
        }

        if ($booking->status !== 'confirmed') {
            $booking->status = 'confirmed';
            $booking->save();
        }

        // Idempotent Payment Record Creation
        $payment = Payment::where('stripe_payment_intent_id', $session->payment_intent)->first();
        if (!$payment) {
            Payment::create([
                'booking_id' => $booking->id,
                'stripe_payment_intent_id' => $session->payment_intent,
                'amount' => $session->amount_total / 100,
                'currency' => $session->currency,
                'status' => 'succeeded',
            ]);
        }

        return $booking;
    }
}
