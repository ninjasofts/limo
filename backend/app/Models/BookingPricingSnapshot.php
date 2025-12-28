<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPricingSnapshot extends Model
{
    protected $fillable = [
        'booking_id',
        'base_price','distance_price','hourly_price','extras_total',
        'subtotal','tax','discount','total',
        'breakdown',
    ];

    protected $casts = [
        'breakdown' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
