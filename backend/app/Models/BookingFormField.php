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
        'options',
        'required',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
