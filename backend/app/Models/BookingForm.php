<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookingFormVersion;
use Illuminate\Database\Eloquent\Relations\HasMany;
class BookingForm extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'currency',
        'services',
        'settings',
        'active',
    ];

    protected $casts = [
        'services' => 'array',
        'settings' => 'array',
    ];

    
    public function fields()
    {
        return $this->hasMany(BookingFormField::class)
            ->orderBy('sort_order');
    }

    public function agreements()
    {
        return $this->hasMany(BookingFormAgreement::class);
    }

     public function versions()
{
    return $this->hasMany(
        BookingFormVersion::class,
        'booking_form_id',
        'id'
    );
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

