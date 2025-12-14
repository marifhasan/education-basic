<?php

namespace App\Filament\Resources\MonthlyFeeGenerations\Pages;

use App\Filament\Resources\MonthlyFeeGenerations\MonthlyFeeGenerationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMonthlyFeeGeneration extends ViewRecord
{
    protected static string $resource = MonthlyFeeGenerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
