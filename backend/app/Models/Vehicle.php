<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_type_id',
        'vehicle_company_id',
        'name',
        'make',
        'model',
        'passengers',
        'luggage',
        'base_price',
        'price_per_km',
        'price_per_hour',
        'active',
    ];

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function company()
    {
        return $this->belongsTo(VehicleCompany::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(
            VehicleAttribute::class,
            'vehicle_attribute_vehicle',   // 👈 explicit pivot table
            'vehicle_id',
            'vehicle_attribute_id'
        )->withPivot('value');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(\App\Models\VehicleInventory::class);
    }



}
