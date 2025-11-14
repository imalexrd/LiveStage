<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function createPaymentIntent(Booking $booking)
    {
        $this->authorize('pay', $booking);

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $booking->total_price * 100, // Amount in cents
            'currency' => 'usd',
            'application_fee_amount' => $booking->total_price * 10, // 10% platform fee
            'transfer_data' => [
                'destination' => $booking->musicianProfile->stripe_connect_id,
            ],
        ]);

        $booking->payment()->create([
            'stripe_payment_intent_id' => $paymentIntent->id,
            'amount' => $booking->total_price,
            'currency' => 'usd',
            'status' => 'processing',
        ]);

        return view('payments.checkout', [
            'clientSecret' => $paymentIntent->client_secret,
            'booking' => $booking,
        ]);
    }
}
