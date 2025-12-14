<?php

namespace App\Filament\Resources\ClassFeeStructures\Pages;

use App\Filament\Resources\ClassFeeStructures\ClassFeeStructureResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditClassFeeStructure extends EditRecord
{
    protected static string $resource = ClassFeeStructureResource::class;

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
