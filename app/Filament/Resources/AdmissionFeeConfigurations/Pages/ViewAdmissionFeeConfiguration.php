<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations\Pages;

use App\Filament\Resources\AdmissionFeeConfigurations\AdmissionFeeConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdmissionFeeConfiguration extends ViewRecord
{
    protected static string $resource = AdmissionFeeConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}