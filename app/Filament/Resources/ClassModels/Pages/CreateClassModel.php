<?php

namespace App\Filament\Resources\ClassModels\Pages;

use App\Filament\Resources\ClassModels\ClassModelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassModel extends CreateRecord
{
    protected static string $resource = ClassModelResource::class;

    protected static ?string $title = 'Create Class';
}
