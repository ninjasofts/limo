<?php

namespace App\Services\Pricing;

use App\Models\Booking;
use App\Models\Vehicle;

class PricingService
{
    public function calculateForVehicles(Booking $booking): PricingResult
    {
        $vehicles = Vehicle::query()
            ->where('active', true)
            ->get();

        if ($vehicles->isEmpty()) {
            return new PricingResult(
                basePrice: 0,
                distancePrice: 0,
                hourlyPrice: 0,
                extrasTotal: 0,
                subtotal: 0,
                tax: 0,
                discount: 0,
                total: 0,
                breakdown: [],
                vehicles: []
            );
        }

        $vehicleResults = [];

        foreach ($vehicles as $vehicle) {
            $base = (float) $vehicle->base_price;
            $distancePrice = 0;
            $hourlyPrice = 0;

            if ($booking->service_type === 'distance') {
                $distancePrice = (float) $booking->distance_km * (float) $vehicle->price_per_km;
            }

            if ($booking->service_type === 'hourly') {
                $hours = ceil(
                    ((int) $booking->duration_min + (int) $booking->extra_time_min) / 60
                );
                $hourlyPrice = $hours * (float) $vehicle->price_per_hour;
            }

            $subtotal = $base + $distancePrice + $hourlyPrice;
            $tax = round($subtotal * 0.10, 2);
            $total = $subtotal + $tax;

            $vehicleResults[] = [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'subtotal' => round($subtotal, 2),
                'tax' => $tax,
                'total' => round($total, 2),
                'capacity' => $vehicle->passenger_capacity,
                'luggage' => $vehicle->luggage_capacity,
            ];
        }

        return new PricingResult(
            basePrice: 0,
            distancePrice: 0,
            hourlyPrice: 0,
            extrasTotal: 0,
            subtotal: 0,
            tax: 0,
            discount: 0,
            total: 0,
            breakdown: [],
            vehicles: $vehicleResults
        );
    }
}
