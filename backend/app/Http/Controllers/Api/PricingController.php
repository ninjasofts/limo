<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Services\Pricing\PricingService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    protected PricingService $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function calculate(Request $request)
    {
        // Minimal validation for MVP
        $request->validate([
            'booking_form_slug' => 'required|string',
            'service_type'      => 'required|string',
            'distance_km'       => 'nullable|numeric',
            'duration_min'      => 'nullable|integer',
            'extra_time_min'    => 'nullable|integer',
            'vehicle_id'        => 'nullable|integer',
        ]);

        // If no vehicle selected yet → return available vehicles
        if (!$request->filled('vehicle_id')) {
            $vehicles = Vehicle::query()
                ->where('active', true)
                ->get()
                ->map(function (Vehicle $v) {
                    return [
                        'id'         => $v->id,
                        'name'       => $v->name,
                        'passengers' => $v->passengers,
                        'luggage'    => $v->luggage,
                        'currency'   => 'EUR',
                        'total'      => $v->base_price,
                    ];
                });

            return response()->json([
                'vehicles' => $vehicles,
                'note' => 'No vehicle selected yet',
            ]);
        }

        // Build a temporary booking object (NOT saved)
        $booking = new Booking([
            'vehicle_id'      => $request->vehicle_id,
            'service_type'    => $request->service_type,
            'distance_km'     => $request->distance_km ?? 0,
            'duration_min'    => $request->duration_min ?? 0,
            'extra_time_min'  => $request->extra_time_min ?? 0,
        ]);

        $result = $this->pricingService->calculate($booking);

        return response()->json([
            'currency' => 'EUR',
            'subtotal' => $result->subtotal,
            'tax'      => $result->tax,
            'discount' => $result->discount,
            'total'    => $result->total,
            'vehicles' => [
                [
                    'id'    => $booking->vehicle_id,
                    'name'  => $booking->vehicle?->name,
                    'total' => $result->total,
                ]
            ],
        ]);
    }
}
