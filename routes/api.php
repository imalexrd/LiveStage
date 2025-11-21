<?php

use App\Http\Controllers\Api\V1\BookingPaymentController;
use App\Http\Controllers\Api\V1\Manager\AvailabilityController;
use App\Http\Controllers\Api\V1\Manager\StripeConnectController as ManagerStripeConnectController;
use App\Http\Controllers\Api\V1\MusicianProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/musicians', [MusicianProfileController::class, 'index']);
Route::get('/v1/musicians/{profile}', [MusicianProfileController::class, 'show']);
Route::post('/v1/musicians', [MusicianProfileController::class, 'store'])->middleware('auth:sanctum');
Route::put('/v1/musicians/{profile}', [MusicianProfileController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/v1/musicians/{profile}', [MusicianProfileController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/v1/manager/stripe/connect', [ManagerStripeConnectController::class, 'connect'])->middleware('auth:sanctum');
Route::post('/v1/bookings/{booking}/pay', [BookingPaymentController::class, 'pay'])->middleware('auth:sanctum');

Route::get('/v1/manager/availability', [AvailabilityController::class, 'index'])->middleware('auth:sanctum');
Route::post('/v1/manager/availability', [AvailabilityController::class, 'store'])->middleware('auth:sanctum');
Route::delete('/v1/manager/availability/{id}', [AvailabilityController::class, 'destroy'])->middleware('auth:sanctum');
