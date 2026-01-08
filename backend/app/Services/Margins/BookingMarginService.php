<?php

namespace App\Services\Margins;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingMarginService
{
    public function recompute(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->refresh();

            $payoutTotal = $booking->providerPayouts()->sum('payout_amount');
            $revenue = (float) $booking->total;

            $margin = max($revenue - (float) $payoutTotal, 0);
            $marginPercent = $revenue > 0 ? round(($margin / $revenue) * 100, 2) : 0;

            $booking->update([
                'provider_payout_total' => $payoutTotal,
                'margin_amount' => $margin,
                'margin_percent' => $marginPercent,
            ]);
        });
    }
}
