<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations\Pages;

use App\Filament\Resources\AdmissionFeeConfigurations\AdmissionFeeConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdmissionFeeConfiguration extends EditRecord
{
    protected static string $resource = AdmissionFeeConfigurationResource::class;

    protected function afterSave(): void
    {
        // Update total fee when saving
        $this->record->updateTotalFee();
    }
}