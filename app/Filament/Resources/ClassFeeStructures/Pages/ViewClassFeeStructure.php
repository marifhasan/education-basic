<?php

namespace App\Filament\Resources\ClassFeeStructures\Pages;

use App\Filament\Resources\ClassFeeStructures\ClassFeeStructureResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClassFeeStructure extends ViewRecord
{
    protected static string $resource = ClassFeeStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
