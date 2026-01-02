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
                $this->record->update([
                    'status' => 'processing',
                ]);

                // ✅ Customer email
                if ($this->record->customer_email) {
                    Mail::to($this->record->customer_email)
                        ->send(new BookingConfirmed($this->record));
                }

                // ✅ Admin email
                Mail::to(config('mail.admin_address'))
                    ->send(new BookingConfirmed($this->record));

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
                        ->send(new BookingCancelled($this->record));
                }

                // ✅ Admin email
                Mail::to(config('mail.admin_address'))
                    ->send(new BookingCancelled($this->record));

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
