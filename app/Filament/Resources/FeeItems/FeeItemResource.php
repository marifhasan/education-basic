<?php

namespace App\Filament\Resources\FeeItems;

use App\Filament\Resources\FeeItems\Pages\CreateFeeItem;
use App\Filament\Resources\FeeItems\Pages\EditFeeItem;
use App\Filament\Resources\FeeItems\Pages\ListFeeItems;
use App\Filament\Resources\FeeItems\Pages\ViewFeeItem;
use App\Filament\Resources\FeeItems\Schemas\FeeItemForm;
use App\Filament\Resources\FeeItems\Schemas\FeeItemInfolist;
use App\Filament\Resources\FeeItems\Tables\FeeItemsTable;
use App\Models\FeeItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class FeeItemResource extends Resource
{
    protected static ?string $model = FeeItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static UnitEnum|string|null $navigationGroup = 'Fee Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FeeItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeeItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeeItemsTable::configure($table);
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
            'index' => ListFeeItems::route('/'),
            'create' => CreateFeeItem::route('/create'),
            'view' => ViewFeeItem::route('/{record}'),
            'edit' => EditFeeItem::route('/{record}/edit'),
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
