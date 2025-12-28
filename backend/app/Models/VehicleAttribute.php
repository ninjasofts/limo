<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleAttribute extends Model
{
    protected $fillable = ['name', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];
}
