<?php
namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\BookingForm;
use App\Support\BookingNumber;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function create(array $payload): Booking
    {
        return DB::transaction(function () use ($payload) {

            // $form = BookingForm::with(['fields', 'agreements'])
            //     ->where('id', $payload['booking_form_id'])
            //     ->where('active', true)
            //     ->firstOrFail();

            $form = BookingForm::whereSlug($payload['slug'])->with(['fields', 'agreements'])->firstOrFail();
            
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


            $fieldIds = $form->fields->pluck('id')->map(fn($i)=>(string)$i)->all();
            $agreementIds = $form->agreements->pluck('id')->map(fn($i)=>(string)$i)->all();


            // Ensure service type is allowed by this form
            if (!in_array($payload['service_type'], $form->services ?? [], true)) {
                abort(422, 'Service type not enabled for this booking form.');
            }

            $booking = Booking::create([
                'booking_number' => BookingNumber::make(),
                'booking_form_id' => $form->id,
                'booking_form_version_id' => $latestVersion->id,
                'vehicle_id' => $payload['vehicle_id'] ?? null,
                'service_type' => $payload['service_type'],
                'transfer_type' => $payload['transfer_type'] ?? 'one_way',

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

                'adults' => $payload['adults'] ?? 1,
                'children' => $payload['children'] ?? 0,
                'luggage' => $payload['luggage'] ?? 0,

                'currency' => $form->currency,

                'customer_first_name' => $payload['customer']['first_name'] ?? null,
                'customer_last_name' => $payload['customer']['last_name'] ?? null,
                'customer_email' => $payload['customer']['email'] ?? null,
                'customer_phone' => $payload['customer']['phone'] ?? null,
                'customer_note' => $payload['customer']['note'] ?? null,
            ]);

            // Save dynamic field values
            foreach (($payload['fields'] ?? []) as $fieldId => $value) {
                if (!in_array((string) $fieldId, $fieldIds, true)) {
                    continue; // field does not belong to this booking form
                }

                $booking->fieldValues()->create([
                    'booking_form_field_id' => (int) $fieldId,
                    'value' => is_array($value)
                        ? json_encode($value)
                        : (string) $value,
                ]);
            }


            // Save agreement acceptance
            foreach (($payload['agreements'] ?? []) as $agreementId => $accepted) {
                if (!$accepted) {
                    continue;
                }

                if (!in_array((string) $agreementId, $agreementIds, true)) {
                    continue; // agreement does not belong to this booking form
                }

                $booking->agreements()->create([
                    'booking_form_agreement_id' => (int) $agreementId,
                    'accepted' => true,
                ]);
            }
         

            return $booking->load(['form', 'vehicle', 'fieldValues', 'agreements', 'extras']);
        });
    }

    public function find(int $id): Booking
    {
        return Booking::with(['form', 'vehicle', 'fieldValues', 'agreements', 'extras'])
            ->findOrFail($id);
    }
}
