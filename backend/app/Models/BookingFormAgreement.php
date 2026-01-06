<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BookingFormAgreement extends Model
{
    protected $fillable = [
        'booking_form_id',
        'label',
        'required',
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    public function bookingForm()
    {
        return $this->belongsTo(BookingForm::class);
    }
}