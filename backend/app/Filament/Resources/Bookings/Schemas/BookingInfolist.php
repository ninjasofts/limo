<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
// use Filament\Infolists\Components\Section;
use Filament\Schemas\Components\Section;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Booking Overview')
                ->columns(4)
                ->components([
                    TextEntry::make('booking_number')->label('Booking #'),
                    TextEntry::make('status')->label('Status'),
                    TextEntry::make('payment_status')->label('Payment'),
                    TextEntry::make('created_at')->label('Created')->dateTime(),

                    TextEntry::make('form.name')->label('Booking Form'),
                    TextEntry::make('booking_form_version_id')->label('Form Version ID')->numeric(),
                    TextEntry::make('service_type')->label('Service Type'),
                    TextEntry::make('transfer_type')->label('Transfer Type'),

                    TextEntry::make('vehicle.name')->label('Vehicle'),
                    TextEntry::make('vehicle.type.name')->label('Vehicle Type'),
                    TextEntry::make('vehicle.company.name')->label('Fleet / Company'),
                    TextEntry::make('b2b_account_id')->label('B2B Account ID')->placeholder('—')->numeric(),
                ]),

            Section::make('Route & Timing')
                ->columns(4)
                ->components([
                    TextEntry::make('pickup_at')->label('Pickup At')->dateTime(),
                    TextEntry::make('return_at')->label('Return At')->placeholder('—')->dateTime(),

                    TextEntry::make('pickup_address')->label('Pickup'),
                    TextEntry::make('dropoff_address')->label('Dropoff'),

                    TextEntry::make('distance_km')->label('Distance (km)')->numeric(),
                    TextEntry::make('duration_min')->label('Duration (min)')->numeric(),
                    TextEntry::make('extra_time_min')->label('Extra Time (min)')->numeric(),

                    RepeatableEntry::make('presentedRouteWaypoints')
                        ->label('Waypoints')
                        ->state(fn ($record) => $record->presentedRouteWaypoints())
                        ->columns(2)
                        ->schema([
                            TextEntry::make('stop')->label('Stop'),
                            TextEntry::make('address')->label('Address'),
                        ]),
                ]),

            Section::make('Customer')
                ->columns(4)
                ->components([
                    TextEntry::make('customer_first_name')->label('First Name'),
                    TextEntry::make('customer_last_name')->label('Last Name'),
                    TextEntry::make('customer_email')->label('Email'),
                    TextEntry::make('customer_phone')->label('Phone'),
                    TextEntry::make('customer_note')->label('Note')->placeholder('—'),
                ]),

            Section::make('Pricing Snapshot (Immutable)')
                ->columns(4)
                ->components([
                    TextEntry::make('currency')->label('Currency'),
                    TextEntry::make('subtotal')->label('Subtotal')->numeric(),
                    TextEntry::make('tax')->label('Tax')->numeric(),
                    TextEntry::make('discount')->label('Discount')->numeric(),
                    TextEntry::make('total')->label('Total')->numeric(),

                    TextEntry::make('pricingSnapshot.base_price')->label('Base')->placeholder('—')->numeric(),
                    TextEntry::make('pricingSnapshot.distance_price')->label('Distance')->placeholder('—')->numeric(),
                    TextEntry::make('pricingSnapshot.hourly_price')->label('Hourly')->placeholder('—')->numeric(),
                    TextEntry::make('pricingSnapshot.extras_total')->label('Extras')->placeholder('—')->numeric(),

                    TextEntry::make('presentedPricingBreakdown')
                        ->label('Breakdown (JSON)')
                        ->state(fn ($record) => $record->presentedPricingBreakdown())
                        ->columnSpanFull(),
                ]),

            Section::make('Form Snapshot (Versioned)')
                ->columns(2)
                ->components([
                    RepeatableEntry::make('presentedCustomerFields')
                        ->label('Customer Inputs (from booking_form_versions.schema + stored values)')
                        ->state(fn ($record) => $record->presentedCustomerFields())
                        ->columns(2)
                        ->schema([
                            TextEntry::make('field')->label('Field'),
                            TextEntry::make('value')->label('Value'),
                        ])
                        ->columnSpanFull(),

                    RepeatableEntry::make('presentedAgreements')
                        ->label('Agreements Accepted (Versioned)')
                        ->state(fn ($record) => $record->presentedAgreements())
                        ->columns(2)
                        ->schema([
                            TextEntry::make('agreement')->label('Agreement'),
                            IconEntry::make('accepted')->label('Accepted')->boolean(),
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
