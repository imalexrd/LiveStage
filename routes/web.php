<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('musician-profile', 'musician-profile')
    ->middleware(['auth', 'isManager'])
    ->name('musician.profile');

require __DIR__.'/auth.php';

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\MusicianProfileController;

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::get('/profiles/{uuid}', [MusicianProfileController::class, 'show'])
    ->name('musician.profile.show');

Route::view('search', 'search')->name('search');

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;

Route::middleware(['auth'])->group(function () {
    Route::view('bookings', 'bookings')->name('bookings');
    Route::post('/profiles/{musicianProfile}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::put('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'createPaymentIntent'])->name('payments.create');
});

use App\Http\Controllers\StripeConnectController;

Route::middleware(['auth', 'isManager'])->group(function () {
    Route::get('/stripe/connect', [StripeConnectController::class, 'createAccountLink'])->name('stripe.connect.create');
    Route::get('/stripe/connect/return', [StripeConnectController::class, 'handleReturn'])->name('stripe.connect.return');
    Route::get('/stripe/connect/refresh', [StripeConnectController::class, 'handleRefresh'])->name('stripe.connect.refresh');
});
