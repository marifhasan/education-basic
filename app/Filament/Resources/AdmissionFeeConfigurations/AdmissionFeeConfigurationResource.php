<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations;

use App\Filament\Resources\AdmissionFeeConfigurations\Pages\CreateAdmissionFeeConfiguration;
use App\Filament\Resources\AdmissionFeeConfigurations\Pages\EditAdmissionFeeConfiguration;
use App\Filament\Resources\AdmissionFeeConfigurations\Pages\ListAdmissionFeeConfigurations;
use App\Filament\Resources\AdmissionFeeConfigurations\Pages\ViewAdmissionFeeConfiguration;
use App\Filament\Resources\AdmissionFeeConfigurations\Schemas\AdmissionFeeConfigurationForm;
use App\Filament\Resources\AdmissionFeeConfigurations\Schemas\AdmissionFeeConfigurationInfolist;
use App\Filament\Resources\AdmissionFeeConfigurations\Tables\AdmissionFeeConfigurationsTable;
use App\Models\AdmissionFeeConfiguration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AdmissionFeeConfigurationResource extends Resource
{
    protected static ?string $model = AdmissionFeeConfiguration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static UnitEnum|string|null $navigationGroup = 'Financial';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Admission Fee Configuration';

    protected static ?string $pluralModelLabel = 'Admission Fee Configurations';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return AdmissionFeeConfigurationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AdmissionFeeConfigurationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdmissionFeeConfigurationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AdmissionFeeItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdmissionFeeConfigurations::route('/'),
            'create' => CreateAdmissionFeeConfiguration::route('/create'),
            'view' => ViewAdmissionFeeConfiguration::route('/{record}'),
            'edit' => EditAdmissionFeeConfiguration::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }
}