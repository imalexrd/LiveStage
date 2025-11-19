<?php

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
