<?php

namespace App\Filament\Resources\Sections;

use App\Filament\Resources\Sections\Pages\CreateSection;
use App\Filament\Resources\Sections\Pages\EditSection;
use App\Filament\Resources\Sections\Pages\ListSections;
use App\Filament\Resources\Sections\Pages\ViewSection;
use App\Filament\Resources\Sections\Schemas\SectionForm;
use App\Filament\Resources\Sections\Schemas\SectionInfolist;
use App\Filament\Resources\Sections\Tables\SectionsTable;
use App\Models\Section;
use App\Services\AcademicYearContext;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function shouldRegisterNavigation(): bool
    {
        // Only show Sections when an academic year is selected
        return AcademicYearContext::hasSelectedYear();
    }

    public static function form(Schema $schema): Schema
    {
        return SectionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SectionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SectionsTable::configure($table);
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
            'index' => ListSections::route('/'),
            'create' => CreateSection::route('/create'),
            'view' => ViewSection::route('/{record}'),
            'edit' => EditSection::route('/{record}/edit'),
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
