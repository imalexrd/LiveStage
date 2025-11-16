<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'search')
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

use App\Http\Controllers\BookingController;

Route::middleware(['auth'])->group(function () {
    Route::view('bookings', 'bookings')->name('bookings');
    Route::post('/profiles/{musicianProfile}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::put('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
});
