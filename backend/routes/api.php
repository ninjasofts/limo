<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Booking\VehicleController;
use App\Http\Controllers\Api\Booking\BookingFormController;


Route::get('ping', fn () => response()->json([
    'ok' => true,
    'app' => config('app.name'),
    'time' => now()->toIso8601String(),
]));

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::post('vehicles', [VehicleController::class, 'store']);
});

// Public frontend
Route::get('booking-forms/{slug}', [BookingFormController::class, 'show']);

// Admin
Route::middleware('auth:sanctum')->group(function () {
    Route::get('booking-forms', [BookingFormController::class, 'index']);
    Route::post('booking-forms', [BookingFormController::class, 'store']);
});