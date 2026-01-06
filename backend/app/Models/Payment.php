<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id','invoice_id','b2b_account_id',
        'provider','status','currency','amount',
        'stripe_payment_intent_id','stripe_charge_id','stripe_event_id',
        'captured_at','refunded_at','meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'captured_at' => 'datetime',
        'refunded_at' => 'datetime',
        'meta' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function b2bAccount()
    {
        return $this->belongsTo(B2bAccount::class);
    }
}
