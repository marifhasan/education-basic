<?php

namespace App\Filament\Resources\StudentAcademicRecords;

use App\Filament\Resources\StudentAcademicRecords\Pages\CreateStudentAcademicRecord;
use App\Filament\Resources\StudentAcademicRecords\Pages\EditStudentAcademicRecord;
use App\Filament\Resources\StudentAcademicRecords\Pages\ListStudentAcademicRecords;
use App\Filament\Resources\StudentAcademicRecords\Pages\ViewStudentAcademicRecord;
use App\Filament\Resources\StudentAcademicRecords\Schemas\StudentAcademicRecordForm;
use App\Filament\Resources\StudentAcademicRecords\Schemas\StudentAcademicRecordInfolist;
use App\Filament\Resources\StudentAcademicRecords\Tables\StudentAcademicRecordsTable;
use App\Models\StudentAcademicRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentAcademicRecordResource extends Resource
{
    protected static ?string $model = StudentAcademicRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StudentAcademicRecordForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentAcademicRecordInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentAcademicRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentAcademicRecords::route('/'),
            'create' => CreateStudentAcademicRecord::route('/create'),
            'view' => ViewStudentAcademicRecord::route('/{record}'),
            'edit' => EditStudentAcademicRecord::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
