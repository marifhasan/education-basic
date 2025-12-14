<?php

namespace App\Filament\Resources\MonthlyFeeGenerations\Pages;

use App\Filament\Resources\MonthlyFeeGenerations\MonthlyFeeGenerationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMonthlyFeeGeneration extends EditRecord
{
    protected static string $resource = MonthlyFeeGenerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
