<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function inventories(): HasMany
    {
        return $this->hasMany(VehicleInventory::class);
    }
}
