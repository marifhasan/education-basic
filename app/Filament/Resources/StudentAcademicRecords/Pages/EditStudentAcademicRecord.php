<?php

namespace App\Filament\Resources\StudentAcademicRecords\Pages;

use App\Filament\Resources\StudentAcademicRecords\StudentAcademicRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentAcademicRecord extends EditRecord
{
    protected static string $resource = StudentAcademicRecordResource::class;

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
