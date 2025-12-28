<?php
namespace App\Services\Pricing;

class PricingResult
{
    public function __construct(
        public float $basePrice,
        public float $distancePrice,
        public float $hourlyPrice,
        public float $extrasTotal,
        public float $subtotal,
        public float $tax,
        public float $discount,
        public float $total,
        public array $breakdown
    ) {}
}
