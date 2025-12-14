<?php

namespace App\Filament\Resources\MonthlyFeePayments\Pages;

use App\Filament\Resources\MonthlyFeePayments\MonthlyFeePaymentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMonthlyFeePayment extends ViewRecord
{
    protected static string $resource = MonthlyFeePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
