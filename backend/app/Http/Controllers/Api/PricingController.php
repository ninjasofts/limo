<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\Pricing\PricingService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function calculate(Request $request, PricingService $pricing)
    {
        $booking = new Booking();
        $booking->service_type = $request->string('service_type');
        $booking->distance_km = (float) $request->input('distance_km', 0);
        $booking->duration_min = (int) $request->input('duration_min', 0);
        $booking->extra_time_min = (int) $request->input('extra_time_min', 0);

        $result = $pricing->calculateForVehicles($booking);

        return response()->json([
            'currency' => 'EUR',
            'vehicles' => $result->vehicles,
        ]);
    }
}
