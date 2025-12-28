<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingFieldValue extends Model
{
    protected $fillable = [
        'booking_id',
        'booking_form_field_id',
        'value',
    ];
}
