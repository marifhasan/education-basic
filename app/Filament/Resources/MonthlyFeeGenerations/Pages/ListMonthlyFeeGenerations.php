<?php

namespace App\Filament\Resources\MonthlyFeeGenerations\Pages;

use App\Filament\Resources\MonthlyFeeGenerations\MonthlyFeeGenerationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonthlyFeeGenerations extends ListRecords
{
    protected static string $resource = MonthlyFeeGenerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
