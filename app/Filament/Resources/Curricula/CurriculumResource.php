<?php

namespace App\Filament\Resources\Curricula;

use App\Filament\Resources\Curricula\Pages\CreateCurriculum;
use App\Filament\Resources\Curricula\Pages\EditCurriculum;
use App\Filament\Resources\Curricula\Pages\ListCurricula;
use App\Filament\Resources\Curricula\Pages\ViewCurriculum;
use App\Filament\Resources\Curricula\Schemas\CurriculumForm;
use App\Filament\Resources\Curricula\Schemas\CurriculumInfolist;
use App\Filament\Resources\Curricula\Tables\CurriculaTable;
use App\Models\Curriculum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CurriculumResource extends Resource
{
    protected static ?string $model = Curriculum::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static UnitEnum|string|null $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Curricula';

    protected static ?string $modelLabel = 'Curriculum';

    protected static ?string $pluralModelLabel = 'Curricula';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CurriculumForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurriculumInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculaTable::configure($table);
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
            'index' => ListCurricula::route('/'),
            'create' => CreateCurriculum::route('/create'),
            'view' => ViewCurriculum::route('/{record}'),
            'edit' => EditCurriculum::route('/{record}/edit'),
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
