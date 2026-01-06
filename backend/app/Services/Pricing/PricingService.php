<?php

namespace App\Services\Pricing;

use App\Models\Booking;
use App\Models\BookingForm;
use App\Models\Vehicle;
use App\Models\VehicleInventory;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PricingService
{
    /**
     * Calculate pricing for a single booking (authoritative)
     */
    public function calculate(Booking $booking): PricingResult
    {
        $vehicle = $booking->vehicle;
        $form = $booking->form;

        if (!$vehicle || !$vehicle->active) {
            throw ValidationException::withMessages([
                'vehicle' => ['Vehicle is not available for pricing.'],
            ]);
        }

        $currency = $form->currency;

        $base = 0.0;
        $distancePrice = 0.0;
        $hourlyPrice = 0.0;

        switch ($booking->service_type) {
            case 'distance':
                if ($booking->distance_km <= 0 || $booking->duration_min <= 0) {
                    throw ValidationException::withMessages([
                        'distance' => ['Invalid distance or duration for distance service.'],
                    ]);
                }

                $base = (float) $vehicle->base_price;
                $distancePrice = $booking->distance_km * (float) $vehicle->price_per_km;
                break;

            case 'hourly':
                if ($booking->duration_min <= 0) {
                    throw ValidationException::withMessages([
                        'duration' => ['Invalid duration for hourly service.'],
                    ]);
                }

                $hours = ceil($booking->duration_min / 60);
                $hourlyPrice = $hours * (float) $vehicle->price_per_hour;
                $base = $hourlyPrice;
                break;

            case 'flat':
                $base = (float) $vehicle->flat_price;
                break;

            default:
                throw ValidationException::withMessages([
                    'service_type' => ['Unsupported service type.'],
                ]);
        }

        $extrasTotal = 0.0;

        $subtotal = round($base + $distancePrice + $hourlyPrice + $extrasTotal, 2);

        $taxRate = (float) ($form->tax_rate ?? 0);
        $tax = round(($subtotal * $taxRate) / 100, 2);

        $discount = 0.0;

        $total = round($subtotal + $tax - $discount, 2);

        return new PricingResult(
            currency: $currency,
            basePrice: $base,
            distancePrice: $distancePrice,
            hourlyPrice: $hourlyPrice,
            extrasTotal: $extrasTotal,
            subtotal: $subtotal,
            tax: $tax,
            discount: $discount,
            total: $total,
            breakdown: [
                'base' => $base,
                'distance' => $distancePrice,
                'hourly' => $hourlyPrice,
                'extras' => $extrasTotal,
                'tax_rate' => $taxRate,
            ]
        );
    }

    /**
     * Calculate prices for all AVAILABLE vehicle types
     * (inventory-aware, provider-agnostic)
     */
    public function calculateForForm(
        BookingForm $form,
        array $payload
    ): Collection {
        $vehicles = Vehicle::query()
            ->where('active', true)
            ->get();

        return $vehicles->map(function (Vehicle $vehicle) use ($form, $payload) {

            // 🔒 INVENTORY CHECK (THIS IS THE KEY CHANGE)
            $inventory = VehicleInventory::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('active', true)
                ->whereHas('provider', fn ($q) => $q->where('active', true))
                ->get();

            $availableQty =
                $inventory->sum('quantity') - $inventory->sum('buffer');

            // ❌ No capacity → do not show this vehicle
            if ($availableQty <= 0) {
                return null;
            }

            try {
                $booking = new Booking([
                    'booking_form_id' => $form->id,
                    'service_type' => $payload['service_type'],
                    'distance_km' => $payload['distance_km'] ?? null,
                    'duration_min' => $payload['duration_min'] ?? null,
                ]);

                $booking->setRelation('vehicle', $vehicle);
                $booking->setRelation('form', $form);

                $pricing = $this->calculate($booking);

                return [
                    'id' => $vehicle->id,
                    'name' => $vehicle->name,
                    'passengers' => $vehicle->passengers,
                    'luggage' => $vehicle->luggage,
                    'available_units' => $availableQty, // 🔥 important for future
                    'currency' => $pricing->currency,
                    'total' => $pricing->total,
                    'breakdown' => $pricing->breakdown,
                ];
            } catch (\Throwable $e) {
                return null;
            }

        })->filter()->values();
    }
}
