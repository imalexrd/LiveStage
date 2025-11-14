<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Illuminate\Support\Facades\Auth;

class StripeConnectController extends Controller
{
    public function createAccountLink()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $manager = Auth::user();
        $musicianProfile = $manager->musicianProfile;

        if (!$musicianProfile->stripe_connect_id) {
            $account = Account::create([
                'type' => 'express',
                'email' => $manager->email,
            ]);
            $musicianProfile->stripe_connect_id = $account->id;
            $musicianProfile->save();
        }

        $accountLink = AccountLink::create([
            'account' => $musicianProfile->stripe_connect_id,
            'refresh_url' => route('stripe.connect.refresh'),
            'return_url' => route('stripe.connect.return'),
            'type' => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    }

    public function handleReturn()
    {
        return redirect()->route('musician.profile')->with('success', 'Stripe account connected successfully!');
    }

    public function handleRefresh()
    {
        return redirect()->route('stripe.connect.create');
    }
}
