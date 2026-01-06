<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAgreement extends Model
{
    protected $fillable = [
        'booking_id',
        'booking_form_agreement_id',
        'accepted',
    ];

    protected $casts = [
        'accepted' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(BookingFormAgreement::class, 'booking_form_agreement_id');
    }
}
