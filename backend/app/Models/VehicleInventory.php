<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleInventory extends Model
{
    protected $fillable = [
        'vehicle_id',
        'provider_id',
        'quantity',
        'buffer',
        'active',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'buffer' => 'integer',
        'active' => 'boolean',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Convenience accessor (not stored): available units after buffer
     */
    public function getAvailableUnitsAttribute(): int
    {
        return max(0, (int) $this->quantity - (int) $this->buffer);
    }
}
