<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class StripeConnectController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function connect()
    {
        $user = Auth::user();

        if ($user->role !== 'manager') {
             abort(403, 'Only managers can connect Stripe accounts.');
        }

        $profile = $user->musicianProfile;

        if (!$profile) {
            return redirect()->route('musician.profile')->with('error', 'You must create a musician profile first.');
        }

        try {
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

            return redirect($accountLink->url);

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
