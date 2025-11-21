<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Account;
use App\Services\StripePaymentService;

class StripeConnectController extends Controller
{
    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function connect()
    {
        try {
            $url = $this->stripeService->createAccountLink(Auth::user());
            return redirect($url);
        } catch (\Exception $e) {
            return redirect()->route('musician.profile')->with('error', 'Error connecting to Stripe: ' . $e->getMessage());
        }
    }

    public function callback()
    {
        $user = Auth::user();
        $profile = $user->musicianProfile;

        if (!$profile || !$profile->stripe_connect_id) {
             return redirect()->route('musician.profile')->with('error', 'Stripe account not found.');
        }

        try {
            $account = Account::retrieve($profile->stripe_connect_id);

            if ($account->details_submitted) {
                return redirect()->route('musician.profile')->with('message', 'Stripe account connected successfully!');
            } else {
                 // User might have clicked "Skip" or not finished.
                 // We'll assume they are working on it, or we can prompt them again.
                 return redirect()->route('musician.profile')->with('error', 'Stripe onboarding incomplete. Please try again.');
            }
        } catch (\Exception $e) {
             return redirect()->route('musician.profile')->with('error', 'Error verifying Stripe account: ' . $e->getMessage());
        }
    }
}
