<?php

namespace App\Filament\Resources\ClassModels\Pages;

use App\Filament\Resources\ClassModels\ClassModelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditClassModel extends EditRecord
{
    protected static string $resource = ClassModelResource::class;

    protected static ?string $title = 'Edit Class';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('View Class'),
            DeleteAction::make()->label('Delete Class'),
            ForceDeleteAction::make()->label('Force Delete Class'),
            RestoreAction::make()->label('Restore Class'),
        ];
    }
}
