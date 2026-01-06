<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Bookings\Schemas\BookingViewSchema;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmed;
use App\Mail\BookingCancelled;
use App\Services\Invoice\InvoiceService;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function resolveRecord(int|string $key): Model
    {
        return Booking::query()
            ->with([
                'form',
                'vehicle.type',
                'vehicle.company',
                'pricingSnapshot',
                'fieldValues',
                'agreements.agreement',
            ])
            ->findOrFail($key);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirm')
                ->label('Confirm')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->canBeConfirmed())
                ->action(function () {
                    // ✅ Confirming a booking is NOT the same as confirming payment.
                    // payment_status must remain a valid enum value.
                    $this->record->update([
                        'status' => 'processing',
                        'payment_status' => $this->record->payment_status ?: 'unpaid',
                        // Keep method stable if present in schema
                        'payment_method' => $this->record->payment_method ?: 'offline',
                    ]);

                    $invoicePath = app(InvoiceService::class)->generate($this->record);

                    // ✅ Customer email
                    if ($this->record->customer_email) {
                        Mail::to($this->record->customer_email)
                            ->queue((new BookingConfirmed($this->record))
                                ->attach($invoicePath));
                    }

                    // ✅ Admin email
                    Mail::to(config('mail.admin_address'))
                        ->queue((new BookingConfirmed($this->record))
                            ->attach($invoicePath));

                    Notification::make()
                        ->title('Booking confirmed')
                        ->success()
                        ->send();
                }),

            Action::make('cancel')
                ->label('Cancel')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->canBeCancelled())
                ->action(function () {
                    $this->record->update([
                        'status' => 'cancelled',
                    ]);

                    // ✅ Customer email
                    if ($this->record->customer_email) {
                        Mail::to($this->record->customer_email)
                            ->queue(new BookingCancelled($this->record));
                    }

                    // ✅ Admin email
                    Mail::to(config('mail.admin_address'))
                        ->queue(new BookingCancelled($this->record));

                    Notification::make()
                        ->title('Booking cancelled')
                        ->danger()
                        ->send();
                }),
        ];
    }

    public function getSchema(string $name): ?Schema
    {
        return BookingViewSchema::make()
            ->livewire($this)
            ->record($this->getRecord());
    }
}
