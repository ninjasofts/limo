<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderPayout extends Model
{
    protected $fillable = [
        'booking_id',
        'provider_id',
        'currency',
        'payout_amount',
        'status',
        'payout_method',
        'reference',
        'paid_at',
        'meta',
    ];

    protected $casts = [
        'payout_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'meta' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
