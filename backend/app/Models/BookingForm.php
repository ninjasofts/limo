<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
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
}

