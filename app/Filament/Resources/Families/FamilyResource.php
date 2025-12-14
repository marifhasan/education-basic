<?php

namespace App\Filament\Resources\Families;

use App\Filament\Resources\Families\Pages\CreateFamily;
use App\Filament\Resources\Families\Pages\EditFamily;
use App\Filament\Resources\Families\Pages\ListFamilies;
use App\Filament\Resources\Families\Pages\ViewFamily;
use App\Filament\Resources\Families\Schemas\FamilyForm;
use App\Filament\Resources\Families\Schemas\FamilyInfolist;
use App\Filament\Resources\Families\Tables\FamiliesTable;
use App\Models\Family;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static UnitEnum|string|null $navigationGroup = 'Student Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'family_code';

    protected static ?string $modelLabel = 'Family';

    protected static ?string $pluralModelLabel = 'Families';

    public static function form(Schema $schema): Schema
    {
        return FamilyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FamilyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FamiliesTable::configure($table);
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
            'index' => ListFamilies::route('/'),
            'create' => CreateFamily::route('/create'),
            'view' => ViewFamily::route('/{record}'),
            'edit' => EditFamily::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->family_code.' - '.$record->father_name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['family_code', 'father_name', 'father_phone', 'mother_name', 'primary_phone'];
    }
}
