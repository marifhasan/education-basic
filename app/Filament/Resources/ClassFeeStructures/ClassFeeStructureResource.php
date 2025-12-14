<?php

namespace App\Filament\Resources\ClassFeeStructures;

use App\Filament\Resources\ClassFeeStructures\Pages\CreateClassFeeStructure;
use App\Filament\Resources\ClassFeeStructures\Pages\EditClassFeeStructure;
use App\Filament\Resources\ClassFeeStructures\Pages\ListClassFeeStructures;
use App\Filament\Resources\ClassFeeStructures\Pages\ViewClassFeeStructure;
use App\Filament\Resources\ClassFeeStructures\Schemas\ClassFeeStructureForm;
use App\Filament\Resources\ClassFeeStructures\Schemas\ClassFeeStructureInfolist;
use App\Filament\Resources\ClassFeeStructures\Tables\ClassFeeStructuresTable;
use App\Models\ClassFeeStructure;
use App\Services\AcademicYearContext;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ClassFeeStructureResource extends Resource
{
    protected static ?string $model = ClassFeeStructure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static UnitEnum|string|null $navigationGroup = 'Fee Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Class Fee Structure';

    protected static ?string $pluralLabel = 'Class Fee Structures';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Filter by selected academic year if one is chosen
        if ($yearId = AcademicYearContext::getSelectedYearId()) {
            $query->where('academic_year_id', $yearId);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return ClassFeeStructureForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClassFeeStructureInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassFeeStructuresTable::configure($table);
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
            'index' => ListClassFeeStructures::route('/'),
            'create' => CreateClassFeeStructure::route('/create'),
            'view' => ViewClassFeeStructure::route('/{record}'),
            'edit' => EditClassFeeStructure::route('/{record}/edit'),
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
