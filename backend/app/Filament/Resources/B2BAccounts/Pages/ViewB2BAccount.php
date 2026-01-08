<?php

namespace App\Filament\Resources\B2bAccounts\Pages;

use App\Filament\Resources\B2bAccounts\B2bAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Services\Billing\B2BMonthlyBillingService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

class ViewB2bAccount extends ViewRecord
{
    protected static string $resource = B2bAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateMonthlyInvoice')
                ->label('Generate Monthly Invoice')
                ->icon('heroicon-o-document-text')
                ->form([
                    Select::make('year')
                        ->options(collect(range(now()->year - 2, now()->year + 1))->mapWithKeys(fn ($y) => [$y => $y])->all())
                        ->default(now()->year)
                        ->required(),
                    Select::make('month')
                        ->options([
                            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',
                            7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec',
                        ])
                        ->default(now()->month)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $svc = app(B2BMonthlyBillingService::class);
                    $invoice = $svc->generateInvoice($this->record, (int) $data['year'], (int) $data['month']);

                    $this->redirect(route('filament.admin.resources.invoices.view', ['record' => $invoice->id]));
                }),
        ];
    }

}
