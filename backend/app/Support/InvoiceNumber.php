<?php

namespace App\Support;

use Illuminate\Support\Str;

class InvoiceNumber
{
    public static function make(): string
    {
        // Stable, sortable-ish, unique enough for now
        // Example: INV-20260106-8F3K2D
        return 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
    }
}
