<?php
namespace App\Services\Booking;

use App\Models\BookingForm;

class BookingFormService
{
    public function list()
    {
        return BookingForm::with(['fields', 'agreements'])
            ->where('active', true)
            ->get();
    }

    public function findBySlug(string $slug): BookingForm
    {
        return BookingForm::with(['fields', 'agreements'])
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();
    }

    public function create(array $data): BookingForm
    {
        return BookingForm::create($data);
    }
}
