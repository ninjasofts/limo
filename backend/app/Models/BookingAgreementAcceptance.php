<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAgreementAcceptance extends Model
{
    protected $fillable = [
        'booking_id',
        'booking_form_agreement_id',
        'accepted',
    ];

    protected $casts = [
        'accepted' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function agreement()
    {
        return $this->belongsTo(BookingFormAgreement::class, 'booking_form_agreement_id');
    }
}
