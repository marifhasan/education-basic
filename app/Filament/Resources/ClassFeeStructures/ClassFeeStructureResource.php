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
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassFeeStructureResource extends Resource
{
    protected static ?string $model = ClassFeeStructure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
