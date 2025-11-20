<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Booking;
use App\Models\Payment;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        if (!$endpoint_secret) {
             // If no secret set (dev), skip signature verification OR just warn.
             // For security, verification is mandatory in prod.
             // For this task, we'll assume it's configured or fail.
             Log::warning('STRIPE_WEBHOOK_SECRET not set.');
        }

        try {
            if ($endpoint_secret) {
                $event = Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } else {
                // Fallback for dev without CLI listen
                $event = \Stripe\Event::constructFrom(
                    json_decode($payload, true)
                );
            }
        } catch(\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $this->handleCheckoutSessionCompleted($session);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleCheckoutSessionCompleted($session)
    {
        $bookingId = $session->metadata->booking_id ?? null;

        if (!$bookingId) {
            Log::error('Stripe Webhook: Booking ID not found in metadata', ['session_id' => $session->id]);
            return;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
             Log::error('Stripe Webhook: Booking not found', ['booking_id' => $bookingId]);
             return;
        }

        // Update Booking Status to 'confirmed' (Paid)
        $booking->status = 'confirmed';
        $booking->save();

        // Create Payment Record
        Payment::create([
            'booking_id' => $booking->id,
            'stripe_payment_intent_id' => $session->payment_intent,
            'amount' => $session->amount_total / 100, // Stripe uses cents
            'currency' => $session->currency,
            'status' => 'succeeded',
        ]);

        Log::info('Booking confirmed via Stripe Webhook', ['booking_id' => $booking->id]);
    }
}
