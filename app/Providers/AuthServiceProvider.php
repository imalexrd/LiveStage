<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\MusicianProfile;
use App\Policies\BookingPolicy;
use App\Policies\MusicianProfilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Booking::class => BookingPolicy::class,
        MusicianProfile::class => MusicianProfilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
