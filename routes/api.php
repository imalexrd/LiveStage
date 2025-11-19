<?php

use App\Http\Controllers\Api\V1\MusicianProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/musicians', [MusicianProfileController::class, 'index']);
