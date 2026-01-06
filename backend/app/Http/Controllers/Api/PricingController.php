<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingForm;
use App\Services\Pricing\PricingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PricingController extends Controller
{
    public function __construct(
        protected PricingService $pricing
    ) {}

    public function calculate(Request $request)
    {
        $payload = $request->validate([
            'booking_form_slug' => 'required|string|exists:booking_forms,slug',
            'service_type' => 'required|in:distance,hourly,flat',
            'distance_km' => 'nullable|numeric|min:0',
            'duration_min' => 'nullable|integer|min:0',
        ]);

        $form = BookingForm::query()
            ->whereSlug($payload['booking_form_slug'])
            ->where('active', true)
            ->firstOrFail();

        $vehicles = $this->pricing->calculateForForm($form, $payload);

        if ($vehicles->isEmpty()) {
            throw ValidationException::withMessages([
                'vehicles' => ['No vehicles available for the selected route.'],
            ]);
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'currency' => $form->currency,
                'vehicles' => $vehicles,
            ],
        ]);
    }
}
