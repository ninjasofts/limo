<?php

namespace App\Services\Pricing;

use App\Models\Booking;
use App\Models\Vehicle;

class PricingService
{
    public function calculate(Booking $booking): PricingResult
    {
        $vehicle = Vehicle::findOrFail($booking->vehicle_id);

        $base = $vehicle->base_price;
        $distancePrice = 0;
        $hourlyPrice = 0;

        if ($booking->service_type === 'distance') {
            $distancePrice = $booking->distance_km * $vehicle->price_per_km;
        }

        if ($booking->service_type === 'hourly') {
            $hours = ceil(($booking->duration_min + $booking->extra_time_min) / 60);
            $hourlyPrice = $hours * $vehicle->price_per_hour;
        }

        $subtotal = $base + $distancePrice + $hourlyPrice;
        $tax = round($subtotal * 0.10, 2); // example VAT
        $total = $subtotal + $tax;

        return new PricingResult(
            basePrice: $base,
            distancePrice: $distancePrice,
            hourlyPrice: $hourlyPrice,
            extrasTotal: 0,
            subtotal: $subtotal,
            tax: $tax,
            discount: 0,
            total: $total,
            breakdown: [
                'base' => $base,
                'distance' => $distancePrice,
                'hourly' => $hourlyPrice,
                'tax' => $tax,
            ]
        );
    }
}
