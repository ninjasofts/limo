<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Booking\BookingFormService;

class BookingFormController extends Controller
{
    public function __construct(
        protected BookingFormService $service
    ) {}

    // Public (frontend)
    public function show(string $slug)
    {
        return response()->json([
            'ok' => true,
            'data' => $this->service->findBySlug($slug),
        ]);
    }

    // Admin only
    public function index()
    {
        return response()->json([
            'ok' => true,
            'data' => $this->service->list(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:booking_forms,slug',
            'currency' => 'required|string|size:3',
            'services' => 'required|array|min:1',
            'settings' => 'nullable|array',
        ]);

        return response()->json([
            'ok' => true,
            'data' => $this->service->create($data),
        ], 201);
    }
}
