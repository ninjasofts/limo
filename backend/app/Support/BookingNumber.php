<?php
namespace App\Support;

use Illuminate\Support\Str;

class BookingNumber
{
    public static function make(): string
    {
        // Example: LF-20251226-8F3A21
        return 'LF-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }
}
