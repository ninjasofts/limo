<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Booking\VehicleService;

class VehicleController extends Controller
{
    public function __construct(
        protected VehicleService $service
    ) {}

    public function index(Request $request)
    {
        return response()->json([
            'ok' => true,
            'data' => $this->service->list($request->all()),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'vehicle_company_id' => 'nullable|exists:vehicle_companies,id',
            'name' => 'required|string',
            'passengers' => 'required|integer|min:1',
            'luggage' => 'nullable|integer|min:0',
            'base_price' => 'numeric|min:0',
            'price_per_km' => 'numeric|min:0',
            'price_per_hour' => 'numeric|min:0',
            'attributes' => 'array',
        ]);

        return response()->json([
            'ok' => true,
            'data' => $this->service->create($data),
        ], 201);
    }
}
