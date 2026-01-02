<?php
namespace App\Services\Invoice;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generate(Booking $booking): string
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'booking' => $booking,
        ]);

        $path = "invoices/{$booking->booking_number}.pdf";

        Storage::disk('local')->put($path, $pdf->output());

        return storage_path("app/{$path}");
    }
}
