<?php

namespace App\Filament\Resources\FeeItems\Pages;

use App\Filament\Resources\FeeItems\FeeItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFeeItem extends ViewRecord
{
    protected static string $resource = FeeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
