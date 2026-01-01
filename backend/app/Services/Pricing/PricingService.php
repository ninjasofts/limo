<?php

namespace App\Services\Pricing;

use App\Models\Booking;
use App\Models\Vehicle;

class PricingService
{
    public function calculate(Booking $booking): PricingResult
    {
        /**
         * STEP 1: No vehicle selected yet
         * → return available vehicles instead of failing
         */
        if (!$booking->vehicle_id) {
            $vehicles = Vehicle::query()
                ->where('active', true)
                ->get()
                ->map(fn ($v) => [
                    'id' => $v->id,
                    'name' => $v->name,
                    'passengers' => $v->passengers,
                    'luggage' => $v->luggage,
                    'base_price' => (float) $v->base_price,
                    'price_per_km' => (float) $v->price_per_km,
                    'price_per_hour' => (float) $v->price_per_hour,
                ])
                ->values()
                ->toArray();

            return PricingResult::empty(
                vehicles: $vehicles,
                currency: $booking->currency ?? 'EUR'
            );
        }

        /**
         * STEP 2: Vehicle selected → calculate price
         */
        $vehicle = Vehicle::where('active', true)
            ->where('id', $booking->vehicle_id)
            ->first();

        if (!$vehicle) {
            return PricingResult::empty(
                vehicles: [],
                currency: $booking->currency ?? 'EUR'
            );
        }

        $base = (float) $vehicle->base_price;
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
        $tax = round($subtotal * 0.10, 2);
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
