<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookingFormVersion;
class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'booking_form_id',
        'vehicle_id',
        'b2b_account_id',
        'service_type',
        'transfer_type',
        'pickup_at',
        'return_at',
        'pickup_address','pickup_lat','pickup_lng',
        'dropoff_address','dropoff_lat','dropoff_lng',
        'waypoints',
        'distance_km','duration_min','extra_time_min',
        'adults','children','luggage',
        'currency','subtotal','tax','discount','total',
        'status','payment_status',
        'customer_first_name','customer_last_name','customer_email','customer_phone','customer_note',
    ];

    protected $casts = [
        'pickup_at' => 'datetime',
        'return_at' => 'datetime',
        'waypoints' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(BookingForm::class, 'booking_form_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function fieldValues()
    {
        return $this->hasMany(BookingFieldValue::class);
    }

    public function agreements()
{
    return $this->hasMany(BookingAgreementAcceptance::class, 'booking_id');
}

    public function extras()
    {
        return $this->hasMany(BookingExtra::class);
    }

    public function pricingSnapshot()
{
    return $this->hasOne(BookingPricingSnapshot::class);
}

public function bookingFormVersion()
{
    return $this->belongsTo(
        BookingFormVersion::class,
        'booking_form_version_id',
        'id'
    );
}

}


