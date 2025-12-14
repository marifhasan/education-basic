<?php

namespace App\Filament\Resources\ClassFeeStructures\Pages;

use App\Filament\Resources\ClassFeeStructures\ClassFeeStructureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClassFeeStructures extends ListRecords
{
    protected static string $resource = ClassFeeStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
