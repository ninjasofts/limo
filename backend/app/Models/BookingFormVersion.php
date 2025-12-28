<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\BookingForm;
use App\Models\Booking;

class BookingFormVersion extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'booking_form_id',
        'version',
        'schema',
        'created_at',
    ];
    protected $casts = [
        'schema' => 'array',
    ];

    public function bookingForm()
    {
        return $this->belongsTo(
            BookingForm::class,
            'booking_form_id',
            'id'
        );
    }

    public function bookings()
    {
        return $this->hasMany(
            Booking::class,
            'booking_form_version_id',
            'id'
        );
    }
    
}