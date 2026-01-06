<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BookingFormField extends Model
{
    protected $fillable = [
        'booking_form_id',
        'label',
        'name',
        'type',
        'required',
        'sort_order',
        'meta',
    ];

    protected $casts = [
        'required' => 'boolean',
        'meta' => 'array',
        'sort_order' => 'integer',

    ];

    public function bookingForm()
    {
        return $this->belongsTo(BookingForm::class);
    }
}
