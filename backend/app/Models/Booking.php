<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'booking_form_id',
        'booking_form_version_id',
        'vehicle_id',
        'b2b_account_id',

        'service_type',
        'transfer_type',

        'pickup_at',
        'return_at',

        'pickup_address', 'pickup_lat', 'pickup_lng',
        'dropoff_address', 'dropoff_lat', 'dropoff_lng',
        'waypoints',

        'distance_km', 'duration_min', 'extra_time_min',

        'adults', 'children', 'luggage',

        'currency', 'subtotal', 'tax', 'discount', 'total',

        'status',
        'payment_status',
        'payment_method',
        'payment_intent_id',

        'customer_first_name', 'customer_last_name',
        'customer_email', 'customer_phone', 'customer_note',
    ];

    protected $casts = [
        'pickup_at' => 'datetime',
        'return_at' => 'datetime',
        'waypoints' => 'array',

        'distance_km' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // =========================
    // Relationships (Domain)
    // =========================

    public function form()
    {
        return $this->belongsTo(BookingForm::class, 'booking_form_id');
    }

    public function formVersion()
    {
        return $this->belongsTo(BookingFormVersion::class, 'booking_form_version_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function b2bAccount()
    {
        return $this->belongsTo(B2BAccount::class);
    }

    public function fieldValues()
    {
        return $this->hasMany(BookingFieldValue::class, 'booking_id');
    }

    public function agreementAcceptances()
    {
        return $this->hasMany(BookingAgreementAcceptance::class, 'booking_id');
    }

    public function extras()
    {
        return $this->hasMany(BookingExtra::class, 'booking_id');
    }

    public function pricingSnapshot()
    {
        return $this->hasOne(BookingPricingSnapshot::class, 'booking_id');
    }

    // =========================
    // State helpers (Read-only)
    // =========================

    public function canBeConfirmed(): bool
    {
        return in_array($this->status, ['pending', 'on_hold'], true);
    }

    public function canBeCancelled(): bool
    {
        return ! in_array($this->status, ['cancelled', 'completed'], true);
    }

    // =========================
    // Presentation helpers (Read-only)
    // =========================

    public function formVersionSchema(): array
    {
        $schema = $this->formVersion?->schema;

        if (is_string($schema)) {
            $decoded = json_decode($schema, true);
            return is_array($decoded) ? $decoded : [];
        }

        return is_array($schema) ? $schema : [];
    }

    public function presentedRouteWaypoints(): array
    {
        $wps = $this->waypoints ?? [];
        if (! is_array($wps)) return [];

        return collect($wps)->map(function ($wp, $i) {
            $address = is_array($wp) ? ($wp['address'] ?? null) : null;

            return [
                'stop' => 'Stop ' . ((int) $i + 1),
                'address' => $address ?: json_encode($wp),
            ];
        })->values()->all();
    }

    public function presentedCustomerFields(): array
    {
        $schema = $this->formVersionSchema();

        $map = collect($schema['fields'] ?? [])
            ->filter(fn ($f) => is_array($f) && isset($f['id']))
            ->keyBy(fn ($f) => (int) ($f['id']));

        return $this->fieldValues
            ->map(function (BookingFieldValue $fv) use ($map) {
                $id = (int) $fv->booking_form_field_id;
                $label = $map->get($id)['label'] ?? ('Field #' . $id);

                return [
                    'field' => $label,
                    'value' => $this->normalizeSnapshotValue($fv->value),
                ];
            })
            ->values()
            ->all();
    }

    public function presentedAgreements(): array
    {
        $schema = $this->formVersionSchema();

        $map = collect($schema['agreements'] ?? [])
            ->filter(fn ($a) => is_array($a) && isset($a['id']))
            ->keyBy(fn ($a) => (int) ($a['id']));

        return $this->agreementAcceptances
            ->map(function (BookingAgreementAcceptance $acc) use ($map) {
                $id = (int) $acc->booking_form_agreement_id;

                $label = $map->get($id)['label']
                    ?? $acc->agreement?->label
                    ?? ('Agreement #' . $id);

                return [
                    'agreement' => $label,
                    'accepted'  => (bool) $acc->accepted,
                ];
            })
            ->values()
            ->all();
    }

    public function presentedPricingBreakdown(): string
    {
        $breakdown = $this->pricingSnapshot?->breakdown ?? null;
        if (empty($breakdown)) return '—';

        return json_encode($breakdown, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function normalizeSnapshotValue(mixed $value): string
    {
        if ($value === null) return '—';

        if (is_array($value)) {
            return implode(', ', array_map('strval', $value));
        }

        if (! is_string($value)) return (string) $value;

        $trim = trim($value);

        if ($trim !== '' && (str_starts_with($trim, '{') || str_starts_with($trim, '['))) {
            $decoded = json_decode($trim, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded)) {
                    return implode(', ', array_map('strval', $decoded));
                }
                return (string) $decoded;
            }
        }

        return $trim === '' ? '—' : $trim;
    }

    // =========================
    // Payment helpers (writes)
    // =========================

    public function markPaymentPending(): void
    {
        // payment_status ENUM: unpaid|partial|paid|refunded|failed
        $this->update(['payment_status' => 'unpaid']);
    }

    public function markPaymentPaid(): void
    {
        $this->update(['payment_status' => 'paid']);
    }

    public function markPaymentFailed(): void
    {
        $this->update(['payment_status' => 'failed']);
    }
}
