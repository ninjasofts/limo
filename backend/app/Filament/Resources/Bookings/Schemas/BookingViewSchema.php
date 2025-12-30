<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\IconEntry;



class BookingViewSchema
{
    public static function make(): Schema
    {
        return Schema::make()
            ->components([

                /* =========================
                 | Booking Overview
                 ========================= */
                Section::make('Booking Overview')
                    ->columns(4)
                    ->components([
                        TextEntry::make('booking_number')->label('Booking #'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('payment_status')->badge(),
                        TextEntry::make('created_at')->dateTime(),

                        TextEntry::make('form.name')->label('Booking Form'),
                        TextEntry::make('booking_form_version_id')->label('Form Version'),
                        TextEntry::make('service_type')->label('Service'),
                        TextEntry::make('transfer_type')->label('Transfer'),

                        TextEntry::make('vehicle.name')->label('Vehicle'),
                        TextEntry::make('vehicle.type.name')->label('Vehicle Type'),
                        TextEntry::make('vehicle.company.name')->label('Fleet'),
                    ]),

                /* =========================
                 | Route & Timing
                 ========================= */
                Section::make('Route & Timing')
                    ->columns(3)
                    ->components([
                        TextEntry::make('pickup_at')->dateTime(),
                        TextEntry::make('return_at')->placeholder('—')->dateTime(),

                        TextEntry::make('pickup_address')->label('Pickup'),
                        TextEntry::make('dropoff_address')->label('Dropoff'),

                        TextEntry::make('distance_km')->suffix(' km'),
                        TextEntry::make('duration_min')->suffix(' min'),
                        TextEntry::make('extra_time_min')->suffix(' min'),

                        RepeatableEntry::make('presentedRouteWaypoints')
                            ->label('Waypoints')
                            ->state(fn ($record) => $record->presentedRouteWaypoints())
                            ->columns(2)
                            ->schema([
                                TextEntry::make('stop'),
                                TextEntry::make('address'),
                            ])
                            ->columnSpanFull(),
                    ]),

                /* =========================
                 | Customer
                 ========================= */
                Section::make('Customer')
                    ->columns(4)
                    ->components([
                        TextEntry::make('customer_first_name')->label('First Name'),
                        TextEntry::make('customer_last_name')->label('Last Name'),
                        TextEntry::make('customer_email')->label('Email'),
                        TextEntry::make('customer_phone')->label('Phone'),
                        TextEntry::make('customer_note')->columnSpanFull()->placeholder('—'),
                    ]),

                /* =========================
                 | Pricing Snapshot
                 ========================= */
                Section::make('Pricing Snapshot (Immutable)')
                    ->columns(4)
                    ->components([
                        TextEntry::make('currency'),
                        TextEntry::make('subtotal')->money(),
                        TextEntry::make('tax')->money(),
                        TextEntry::make('discount')->money(),
                        TextEntry::make('total')->money(),

                        TextEntry::make('pricingSnapshot.base_price')->money()->placeholder('—'),
                        TextEntry::make('pricingSnapshot.extras_total')->money()->placeholder('—'),

                        TextEntry::make('presentedPricingBreakdown')
                            ->label('Pricing Breakdown (JSON)')
                            ->state(fn ($record) => $record->presentedPricingBreakdown())
                            ->columnSpanFull(),
                    ]),

                /* =========================
                 | Versioned Form Snapshot
                 ========================= */
                Section::make('Form Snapshot (Versioned)')
                    ->components([

                        RepeatableEntry::make('presentedCustomerFields')
                            ->label('Customer Inputs')
                            ->state(fn ($record) => $record->presentedCustomerFields())
                            ->columns(2)
                            ->schema([
                                TextEntry::make('field'),
                                TextEntry::make('value'),
                            ])
                            ->columnSpanFull(),

                        RepeatableEntry::make('presentedAgreements')
                            ->label('Accepted Agreements')
                            ->state(fn ($record) => $record->presentedAgreements())
                            ->columns(2)
                            ->schema([
                                TextEntry::make('agreement'),
                                IconEntry::make('accepted')->boolean(),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
