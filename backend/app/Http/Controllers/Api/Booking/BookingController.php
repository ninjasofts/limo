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
            // 'booking_form_id' => 'required|exists:booking_forms,id',
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

            'waypoints' => 'nullable|array',
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
        ]);

        $booking = $this->service->create($payload);

        Mail::to($booking->customer_email)->queue(new BookingCreated($booking));
        Mail::to(config('mail.admin_address'))->queue(new BookingCreated($booking));
            

        // Pricing snapshot + update booking totals
        $result = $this->pricing->calculate($booking);

        // If you want snapshot table, add model + relationship (next section)
        if (method_exists($booking, 'pricingSnapshot')) {
            $booking->pricingSnapshot()->create([
                'base_price' => $result->basePrice,
                'distance_price' => $result->distancePrice,
                'hourly_price' => $result->hourlyPrice,
                'extras_total' => $result->extrasTotal,
                'subtotal' => $result->subtotal,
                'tax' => $result->tax,
                'discount' => $result->discount,
                'total' => $result->total,
                'breakdown' => $result->breakdown,
                'payment_method' => $payload['payment_method'],
                    'payment_status' => $payload['payment_method'] === 'card'
                        ? 'pending'
                        : 'unpaid',

            ]);
        }

        $booking->update([
            'subtotal' => $result->subtotal,
            'tax' => $result->tax,
            'discount' => $result->discount,
            'total' => $result->total,
            'payment_method' => 'required|in:card,offline,pay_later',
            'payment_method' => $payload['payment_method'] ?? 'offline',
            'payment_intent_id' => 'pi_' . uniqid(),
            'payment_status' => in_array($payload['payment_method'], ['offline', 'pay_later'])
                ? 'pending'
                : 'unpaid',
        ]);

        return response()->json(['ok' => true, 'data' => $booking], 201);
    }

    public function show(int $id)
    {
        return response()->json([
            'ok' => true,
            'data' => $this->service->find($id),
        ]);
    }
}
