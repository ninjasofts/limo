<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Services\Booking\BookingService;
use App\Services\Pricing\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCreated;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $service,
        protected PricingService $pricing
    ) {}

    public function store(Request $request)
    {
        $payload = $request->validate([
            'client_request_id' => 'nullable|uuid',

            'slug' => 'required|string|exists:booking_forms,slug',
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'required|in:distance,hourly,flat',
            'transfer_type' => 'nullable|in:one_way,return,return_new_ride',

            'pickup_at' => 'required|date',
            'return_at' => 'nullable|date',

            'pickup.address' => 'required|string',
            'pickup.lat' => 'nullable|numeric',
            'pickup.lng' => 'nullable|numeric',

            'dropoff.address' => 'nullable|string',
            'dropoff.lat' => 'nullable|numeric',
            'dropoff.lng' => 'nullable|numeric',

            // waypoints validation (structure)
            'waypoints' => 'nullable|array',
            'waypoints.*.address' => 'nullable|string',
            'waypoints.*.lat' => 'nullable|numeric',
            'waypoints.*.lng' => 'nullable|numeric',

            'distance_km' => 'nullable|numeric|min:0',
            'duration_min' => 'nullable|integer|min:0',
            'extra_time_min' => 'nullable|integer|min:0',

            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'luggage' => 'nullable|integer|min:0',

            'customer.first_name' => 'nullable|string',
            'customer.last_name' => 'nullable|string',
            'customer.email' => 'nullable|email',
            'customer.phone' => 'nullable|string',
            'customer.note' => 'nullable|string',

            'fields' => 'nullable|array',
            'agreements' => 'nullable|array',

            'payment_method' => 'nullable|in:card,offline,pay_later',
        ]);

        $booking = $this->service->create($payload);

        // ✅ Guard: only email customer if email exists
        if (!empty($booking->customer_email)) {
            Mail::to($booking->customer_email)->queue(new BookingCreated($booking));
        }
        Mail::to(config('mail.admin_address'))->queue(new BookingCreated($booking));

        // Pricing snapshot + update booking totals
        $result = $this->pricing->calculate($booking);

        $booking->pricingSnapshot()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'base_price' => $result->basePrice,
                'distance_price' => $result->distancePrice,
                'hourly_price' => $result->hourlyPrice,
                'extras_total' => $result->extrasTotal,
                'subtotal' => $result->subtotal,
                'tax' => $result->tax,
                'discount' => $result->discount,
                'total' => $result->total,
                'breakdown' => $result->breakdown,
            ]
        );

        $paymentMethod = $payload['payment_method'] ?? 'offline';

        $booking->update([
            'subtotal' => $result->subtotal,
            'tax' => $result->tax,
            'discount' => $result->discount,
            'total' => $result->total,

            'payment_method' => $paymentMethod,
            'payment_intent_id' => $paymentMethod === 'card' ? ('pi_' . uniqid()) : null,

            // IMPORTANT: must match ENUM values
            'payment_status' => 'unpaid',
        ]);

        // ✅ If it existed already (idempotency), return 200. Otherwise 201.
        $status = $booking->wasRecentlyCreated ? 201 : 200;

        return response()->json(['ok' => true, 'data' => $booking], $status);
    }

    public function show(int $id)
    {
        return response()->json([
            'ok' => true,
            'data' => $this->service->find($id),
        ]);
    }
}
