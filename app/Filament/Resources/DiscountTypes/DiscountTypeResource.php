<?php

namespace App\Filament\Resources\DiscountTypes;

use App\Filament\Resources\DiscountTypes\Pages\CreateDiscountType;
use App\Filament\Resources\DiscountTypes\Pages\EditDiscountType;
use App\Filament\Resources\DiscountTypes\Pages\ListDiscountTypes;
use App\Filament\Resources\DiscountTypes\Pages\ViewDiscountType;
use App\Filament\Resources\DiscountTypes\Schemas\DiscountTypeForm;
use App\Filament\Resources\DiscountTypes\Schemas\DiscountTypeInfolist;
use App\Filament\Resources\DiscountTypes\Tables\DiscountTypesTable;
use App\Models\DiscountType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DiscountTypeResource extends Resource
{
    protected static ?string $model = DiscountType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static UnitEnum|string|null $navigationGroup = 'Fee Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DiscountTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DiscountTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountTypesTable::configure($table);
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
            'index' => ListDiscountTypes::route('/'),
            'create' => CreateDiscountType::route('/create'),
            'view' => ViewDiscountType::route('/{record}'),
            'edit' => EditDiscountType::route('/{record}/edit'),
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
