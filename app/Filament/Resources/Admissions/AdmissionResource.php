<?php

namespace App\Filament\Resources\Admissions;

use App\Filament\Resources\Admissions\Pages\CreateAdmission;
use App\Filament\Resources\Admissions\Pages\EditAdmission;
use App\Filament\Resources\Admissions\Pages\ListAdmissions;
use App\Filament\Resources\Admissions\Pages\ViewAdmission;
use App\Filament\Resources\Admissions\Schemas\AdmissionForm;
use App\Filament\Resources\Admissions\Schemas\AdmissionInfolist;
use App\Filament\Resources\Admissions\Tables\AdmissionsTable;
use App\Models\Admission;
use App\Services\AcademicYearContext;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AdmissionResource extends Resource
{
    protected static ?string $model = Admission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static UnitEnum|string|null $navigationGroup = 'Admissions';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'admission_number';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Only show Admissions when an academic year is selected
        return AcademicYearContext::hasSelectedYear();
    }

    public static function form(Schema $schema): Schema
    {
        return AdmissionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AdmissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdmissionsTable::configure($table);
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
            'index' => ListAdmissions::route('/'),
            'create' => CreateAdmission::route('/create'),
            'view' => ViewAdmission::route('/{record}'),
            'edit' => EditAdmission::route('/{record}/edit'),
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
        return $record->admission_number.' - '.$record->applicant_first_name.' '.$record->applicant_last_name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['admission_number', 'applicant_first_name', 'applicant_last_name', 'family.family_code'];
    }
}
