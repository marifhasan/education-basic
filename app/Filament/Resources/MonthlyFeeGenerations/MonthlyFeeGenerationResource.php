<?php

namespace App\Filament\Resources\MonthlyFeeGenerations;

use App\Filament\Resources\MonthlyFeeGenerations\Pages\CreateMonthlyFeeGeneration;
use App\Filament\Resources\MonthlyFeeGenerations\Pages\EditMonthlyFeeGeneration;
use App\Filament\Resources\MonthlyFeeGenerations\Pages\ListMonthlyFeeGenerations;
use App\Filament\Resources\MonthlyFeeGenerations\Pages\ViewMonthlyFeeGeneration;
use App\Filament\Resources\MonthlyFeeGenerations\Schemas\MonthlyFeeGenerationForm;
use App\Filament\Resources\MonthlyFeeGenerations\Schemas\MonthlyFeeGenerationInfolist;
use App\Filament\Resources\MonthlyFeeGenerations\Tables\MonthlyFeeGenerationsTable;
use App\Models\MonthlyFeeGeneration;
use App\Services\AcademicYearContext;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MonthlyFeeGenerationResource extends Resource
{
    protected static ?string $model = MonthlyFeeGeneration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function shouldRegisterNavigation(): bool
    {
        // Only show Monthly Fee Generation when an academic year is selected
        return AcademicYearContext::hasSelectedYear();
    }

    public static function form(Schema $schema): Schema
    {
        return MonthlyFeeGenerationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MonthlyFeeGenerationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MonthlyFeeGenerationsTable::configure($table);
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
            'index' => ListMonthlyFeeGenerations::route('/'),
            'create' => CreateMonthlyFeeGeneration::route('/create'),
            'view' => ViewMonthlyFeeGeneration::route('/{record}'),
            'edit' => EditMonthlyFeeGeneration::route('/{record}/edit'),
        ];
    }
}
