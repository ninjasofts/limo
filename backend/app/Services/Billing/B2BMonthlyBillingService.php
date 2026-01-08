<?php

namespace App\Services\Billing;

use App\Models\B2bAccount;
use App\Models\Booking;
use App\Models\Invoice;
use App\Support\InvoiceNumber;
use Illuminate\Support\Facades\DB;

class B2BMonthlyBillingService
{
    public function generateInvoice(B2bAccount $account, int $year, int $month): Invoice
    {
        return DB::transaction(function () use ($account, $year, $month) {

            $start = now()->setDate($year, $month, 1)->startOfDay();
            $end = (clone $start)->endOfMonth()->endOfDay();

            // 1) Select eligible bookings (not already billed)
            $bookings = Booking::query()
                ->where('b2b_account_id', $account->id)
                ->whereNull('billed_at')
                ->whereBetween('created_at', [$start, $end])
                ->lockForUpdate()
                ->get();

            if ($bookings->isEmpty()) {
                throw new \RuntimeException("No eligible bookings found for billing period.");
            }

            // 2) Aggregate totals
            $currency = $bookings->first()->currency;

            // enforce same currency in one consolidated invoice (primitive rule)
            if ($bookings->pluck('currency')->unique()->count() > 1) {
                throw new \RuntimeException("Mixed currencies found. Cannot consolidate in one invoice.");
            }

            $subtotal = $bookings->sum('subtotal');
            $tax = $bookings->sum('tax');
            $discount = $bookings->sum('discount');
            $total = $bookings->sum('total');

            // 3) Create consolidated invoice
            $invoice = Invoice::create([
                'booking_id' => null,
                'b2b_account_id' => $account->id,
                'invoice_number' => InvoiceNumber::make(),
                'status' => 'issued',
                'currency' => $currency,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'issued_at' => now(),
                'due_at' => now()->addDays((int) $account->net_days),
            ]);

            // 4) Mark bookings as billed & link them
            foreach ($bookings as $booking) {
                $booking->update([
                    'b2b_invoice_id' => $invoice->id,
                    'billed_at' => now(),
                ]);
            }

            return $invoice;
        });
    }
}
