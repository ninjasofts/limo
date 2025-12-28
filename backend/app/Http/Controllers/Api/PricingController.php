<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\Pricing\PricingService;

class PricingController extends Controller
{
   public function calculate(Request $request, PricingService $pricing)
{
    $booking = new Booking($request->all());

    $result = $pricing->calculate($booking);

    return response()->json($result);
}
}

