<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCompany extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
