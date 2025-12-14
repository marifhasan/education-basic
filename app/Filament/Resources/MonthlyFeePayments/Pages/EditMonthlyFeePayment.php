<?php

namespace App\Filament\Resources\MonthlyFeePayments\Pages;

use App\Filament\Resources\MonthlyFeePayments\MonthlyFeePaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMonthlyFeePayment extends EditRecord
{
    protected static string $resource = MonthlyFeePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
