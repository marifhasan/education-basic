<?php

namespace App\Filament\Resources\MonthlyFeePayments\Pages;

use App\Filament\Resources\MonthlyFeePayments\MonthlyFeePaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonthlyFeePayments extends ListRecords
{
    protected static string $resource = MonthlyFeePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
