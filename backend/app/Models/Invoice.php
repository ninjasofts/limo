<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id','b2b_account_id',
        'invoice_number','status',
        'currency','subtotal','tax','discount','total',
        'issued_at','due_at','pdf_path',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function B2bAccount()
    {
        return $this->belongsTo(B2bAccount::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
