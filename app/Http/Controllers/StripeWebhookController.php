<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Services\StripePaymentService;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request, StripePaymentService $paymentService)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        if (!$endpoint_secret) {
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
            $paymentService->handlePaymentSuccess($session->id);
            Log::info('Stripe Webhook: Handled payment success', ['session_id' => $session->id]);
        }

        return response()->json(['status' => 'success']);
    }
}
