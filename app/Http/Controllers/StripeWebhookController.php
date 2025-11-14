<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
        $endpointSecret = config('services.stripe.webhook.secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            $booking = Booking::whereHas('payment', function ($query) use ($paymentIntent) {
                $query->where('stripe_payment_intent_id', $paymentIntent->id);
            })->first();

            if ($booking) {
                $booking->update(['status' => 'confirmed']);
                $booking->payment->update(['status' => 'succeeded']);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
