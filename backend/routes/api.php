<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Booking\BookingFormController;

Route::get('ping', fn () => response()->json([
    'ok' => true,
    'app' => config('app.name'),
    'time' => now()->toIso8601String(),
]));

/**
 * AUTH
 */
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });
});

/**
 * PUBLIC FRONTEND (Website booking form consumes these)
 */
Route::get('vehicles', [VehicleController::class, 'index']);
Route::get('booking-forms/{slug}', [BookingFormController::class, 'show']);
Route::post('bookings/calculate', [PricingController::class, 'calculate']);
Route::post('bookings', [BookingController::class, 'store']);

/**
 * ADMIN / DASHBOARD (Protected)
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('vehicles', [VehicleController::class, 'store']);

    Route::get('booking-forms', [BookingFormController::class, 'index']);
    Route::post('booking-forms', [BookingFormController::class, 'store']);

    Route::get('bookings/{id}', [BookingController::class, 'show']);

    // Optional: debug route only in local
    if (app()->environment('local')) {
        Route::get('debug/vehicles', fn () => \App\Models\Vehicle::all());
    }
});

// Middleware-protected routes for public API clients
Route::middleware(['public.signature'])->group(function () {

    Route::middleware('throttle:public-read')->group(function () {
        Route::get('/vehicles', [\App\Http\Controllers\Api\VehicleController::class, 'index']);
        Route::get('/booking-forms', [\App\Http\Controllers\Api\Booking\BookingFormController::class, 'index']);
        Route::get('/booking-forms/{slug}', [\App\Http\Controllers\Api\Booking\BookingFormController::class, 'show']);
    });

    Route::middleware('throttle:public-calc')->group(function () {
        Route::post('/bookings/calculate', [\App\Http\Controllers\Api\PricingController::class, 'calculate']);
    });

    Route::middleware('throttle:public-book')->group(function () {
        Route::post('/bookings', [\App\Http\Controllers\Api\Booking\BookingController::class, 'store']);
    });

});
