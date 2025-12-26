<?php
namespace App\Services\Booking;

use App\Models\Vehicle;

class VehicleService
{
    public function list(array $filters = [])
    {
        return Vehicle::with(['type', 'company', 'attributes'])
            ->where('active', true)
            ->when($filters['passengers'] ?? null, fn ($q, $p) =>
                $q->where('passengers', '>=', $p)
            )
            ->get();
    }

    public function create(array $data): Vehicle
    {
        $vehicle = Vehicle::create($data);

        if (!empty($data['attributes'])) {
            foreach ($data['attributes'] as $attrId => $value) {
                $vehicle->attributes()->attach($attrId, ['value' => $value]);
            }
        }

        return $vehicle->load(['type', 'company', 'attributes']);
    }
}
