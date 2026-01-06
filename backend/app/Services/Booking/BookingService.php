<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\BookingForm;
use App\Models\Vehicle;
use App\Support\BookingNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function create(array $payload): Booking
    {
        return DB::transaction(function () use ($payload) {

            /**
             * 0) Idempotency (prevents duplicate bookings on retries)
             */
            $clientRequestId = $payload['client_request_id'] ?? null;
            if ($clientRequestId) {
                $existing = Booking::query()
                    ->where('client_request_id', $clientRequestId)
                    ->first();

                if ($existing) {
                    return $existing->load(['form', 'vehicle', 'fieldValues', 'agreements', 'extras', 'pricingSnapshot']);
                }
            }

            /**
             * 1) Load form (must be active)
             */
            $form = BookingForm::query()
                ->whereSlug($payload['slug'])
                ->where('active', true)
                ->with(['fields', 'agreements'])
                ->firstOrFail();

            $latestVersion = $form->versions()->latest('version')->first();
            if (!$latestVersion) {
                $latestVersion = $form->versions()->create([
                    'version' => 1,
                    'schema' => [
                        'fields' => $form->fields->toArray(),
                        'agreements' => $form->agreements->toArray(),
                    ],
                ]);
            }

            /**
             * 2) Validate service type enabled by this form (hard stop)
             */
            if (!in_array($payload['service_type'], $form->services ?? [], true)) {
                throw ValidationException::withMessages([
                    'service_type' => ['Service type not enabled for this booking form.'],
                ]);
            }

            /**
             * 3) Vehicle must exist + be active
             */
            $vehicle = Vehicle::query()
                ->where('active', true)
                ->where('id', (int) ($payload['vehicle_id'] ?? 0))
                ->first();

            if (!$vehicle) {
                throw ValidationException::withMessages([
                    'vehicle_id' => ['Invalid or inactive vehicle.'],
                ]);
            }

            /**
             * 4) Domain rules (service-specific requirements)
             */
            $serviceType = $payload['service_type'];
            $transferType = $payload['transfer_type'] ?? 'one_way';

            // Dropoff required for distance + flat
            if (in_array($serviceType, ['distance', 'flat'], true)) {
                if (empty($payload['dropoff']['address'] ?? null)) {
                    throw ValidationException::withMessages([
                        'dropoff.address' => ['Drop-off address is required for this service type.'],
                    ]);
                }
            }

            // Distance requires distance + duration
            if ($serviceType === 'distance') {
                if (!isset($payload['distance_km']) || (float) $payload['distance_km'] <= 0) {
                    throw ValidationException::withMessages([
                        'distance_km' => ['Distance is required and must be greater than 0 for distance service.'],
                    ]);
                }
                if (!isset($payload['duration_min']) || (int) $payload['duration_min'] <= 0) {
                    throw ValidationException::withMessages([
                        'duration_min' => ['Duration is required and must be greater than 0 for distance service.'],
                    ]);
                }
            }

            // Hourly requires duration
            if ($serviceType === 'hourly') {
                if (!isset($payload['duration_min']) || (int) $payload['duration_min'] <= 0) {
                    throw ValidationException::withMessages([
                        'duration_min' => ['Duration is required and must be greater than 0 for hourly service.'],
                    ]);
                }
            }

            // Return rides require return_at > pickup_at
            if (in_array($transferType, ['return', 'return_new_ride'], true)) {
                if (empty($payload['return_at'] ?? null)) {
                    throw ValidationException::withMessages([
                        'return_at' => ['Return time is required for this transfer type.'],
                    ]);
                }
            }

            /**
             * 5) Capacity validation (adults+children <= passengers, luggage <= luggage)
             */
            $adults = (int) ($payload['adults'] ?? 1);
            $children = (int) ($payload['children'] ?? 0);
            $luggage = (int) ($payload['luggage'] ?? 0);

            if (($adults + $children) > (int) $vehicle->passengers) {
                throw ValidationException::withMessages([
                    'adults' => ['Passengers exceed vehicle capacity. Choose a larger vehicle.'],
                ]);
            }

            if ($luggage > (int) $vehicle->luggage) {
                throw ValidationException::withMessages([
                    'luggage' => ['Luggage exceeds vehicle capacity. Choose a larger vehicle.'],
                ]);
            }

            /**
             * 6) Enforce required dynamic fields (booking_form_fields.required)
             */
            $requiredFieldIds = $form->fields
                ->where('required', true)
                ->pluck('id')
                ->map(fn ($i) => (string) $i)
                ->values()
                ->all();

            $payloadFields = is_array($payload['fields'] ?? null) ? $payload['fields'] : [];

            foreach ($requiredFieldIds as $reqId) {
                if (!array_key_exists($reqId, $payloadFields)) {
                    throw ValidationException::withMessages([
                        "fields.$reqId" => ['This field is required.'],
                    ]);
                }

                $val = $payloadFields[$reqId];

                $missing =
                    $val === null ||
                    (is_string($val) && trim($val) === '') ||
                    (is_array($val) && count($val) === 0);

                if ($missing) {
                    throw ValidationException::withMessages([
                        "fields.$reqId" => ['This field is required.'],
                    ]);
                }
            }

            /**
             * 7) Enforce required agreements (booking_form_agreements.required)
             */
            $requiredAgreementIds = $form->agreements
                ->where('required', true)
                ->pluck('id')
                ->map(fn ($i) => (string) $i)
                ->values()
                ->all();

            $payloadAgreements = is_array($payload['agreements'] ?? null) ? $payload['agreements'] : [];

            foreach ($requiredAgreementIds as $reqAgreementId) {
                $accepted = $payloadAgreements[$reqAgreementId] ?? false;
                if (!$accepted) {
                    throw ValidationException::withMessages([
                        "agreements.$reqAgreementId" => ['You must accept this agreement.'],
                    ]);
                }
            }

            /**
             * 8) Create booking
             */
            $booking = Booking::create([
                'booking_number' => BookingNumber::make(),
                'client_request_id' => $clientRequestId,

                'booking_form_id' => $form->id,
                'booking_form_version_id' => $latestVersion->id,

                'vehicle_id' => (int) $vehicle->id,
                'service_type' => $serviceType,
                'transfer_type' => $transferType,

                'pickup_at' => $payload['pickup_at'],
                'return_at' => $payload['return_at'] ?? null,

                'pickup_address' => $payload['pickup']['address'],
                'pickup_lat' => $payload['pickup']['lat'] ?? null,
                'pickup_lng' => $payload['pickup']['lng'] ?? null,

                'dropoff_address' => $payload['dropoff']['address'] ?? null,
                'dropoff_lat' => $payload['dropoff']['lat'] ?? null,
                'dropoff_lng' => $payload['dropoff']['lng'] ?? null,

                'waypoints' => $payload['waypoints'] ?? null,

                'distance_km' => $payload['distance_km'] ?? null,
                'duration_min' => $payload['duration_min'] ?? null,
                'extra_time_min' => $payload['extra_time_min'] ?? 0,

                'adults' => $adults,
                'children' => $children,
                'luggage' => $luggage,

                'currency' => $form->currency,

                'customer_first_name' => $payload['customer']['first_name'] ?? null,
                'customer_last_name' => $payload['customer']['last_name'] ?? null,
                'customer_email' => $payload['customer']['email'] ?? null,
                'customer_phone' => $payload['customer']['phone'] ?? null,
                'customer_note' => $payload['customer']['note'] ?? null,
            ]);

            /**
             * 8.1) Upsert customer (CRM primitive)
             * WHY: Keep snapshot on booking, but also maintain a durable customer record for history & repeat bookings.
             */
            $custEmail = $payload['customer']['email'] ?? null;
            $custPhone = $payload['customer']['phone'] ?? null;

            if (!empty($custEmail) || !empty($custPhone)) {
                $customerQuery = \App\Models\Customer::query();

                if (!empty($custEmail)) {
                    $customerQuery->where('email', $custEmail);
                }

                if (!empty($custPhone)) {
                    $customerQuery->orWhere('phone', $custPhone);
                }

                $customer = $customerQuery->first();

                if (!$customer) {
                    $customer = \App\Models\Customer::create([
                        'first_name' => $payload['customer']['first_name'] ?? null,
                        'last_name'  => $payload['customer']['last_name'] ?? null,
                        'email'      => $custEmail,
                        'phone'      => $custPhone,
                        'last_seen_at' => now(),
                        'bookings_count' => 0,
                    ]);
                } else {
                    $customer->fill([
                        'first_name' => $customer->first_name ?: ($payload['customer']['first_name'] ?? null),
                        'last_name'  => $customer->last_name  ?: ($payload['customer']['last_name'] ?? null),
                        'email'      => $customer->email      ?: $custEmail,
                        'phone'      => $customer->phone      ?: $custPhone,
                        'last_seen_at' => now(),
                    ])->save();
                }

                $customer->increment('bookings_count');

                $booking->update(['customer_id' => $customer->id]);
            }

            /**
             * 9) Persist only fields/agreements belonging to this form (existing behavior kept)
             */
            $fieldIds = $form->fields->pluck('id')->map(fn ($i) => (string) $i)->all();
            foreach ($payloadFields as $fieldId => $value) {
                if (!in_array((string) $fieldId, $fieldIds, true)) {
                    continue;
                }

                $booking->fieldValues()->create([
                    'booking_form_field_id' => (int) $fieldId,
                    'value' => is_array($value) ? json_encode($value) : (string) $value,
                ]);
            }

            $agreementIds = $form->agreements->pluck('id')->map(fn ($i) => (string) $i)->all();
            foreach ($payloadAgreements as $agreementId => $accepted) {
                if (!$accepted) continue;
                if (!in_array((string) $agreementId, $agreementIds, true)) continue;

                $booking->agreements()->create([
                    'booking_form_agreement_id' => (int) $agreementId,
                    'accepted' => true,
                ]);
            }

            return $booking->load(['form', 'vehicle', 'fieldValues', 'agreements', 'extras', 'pricingSnapshot']);
        });
    }

    

    public function find(int $id): Booking
    {
        return Booking::with(['form', 'vehicle', 'fieldValues', 'agreements', 'extras', 'pricingSnapshot'])
            ->findOrFail($id);
    }
}
