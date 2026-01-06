<?php

namespace App\Services\Invoice;

use App\Models\Booking;
use App\Models\Invoice;
use App\Support\InvoiceNumber;
use Illuminate\Support\Facades\DB;

class InvoicePrimitiveService
{
    public function createFromBooking(Booking $booking, ?int $b2bAccountId = null): Invoice
    {
        return DB::transaction(function () use ($booking, $b2bAccountId) {

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'b2b_account_id' => $b2bAccountId ?? $booking->b2b_account_id,
                'invoice_number' => InvoiceNumber::make(),
                'status' => 'issued',
                'currency' => $booking->currency,
                'subtotal' => $booking->subtotal,
                'tax' => $booking->tax,
                'discount' => $booking->discount,
                'total' => $booking->total,
                'issued_at' => now(),
                'due_at' => ($booking->b2b_account_id ? now()->addDays(14) : now()), // simple primitive
            ]);

            return $invoice;
        });
    }
}
