<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations\Pages;

use App\Filament\Resources\AdmissionFeeConfigurations\AdmissionFeeConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdmissionFeeConfigurations extends ListRecords
{
    protected static string $resource = AdmissionFeeConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}