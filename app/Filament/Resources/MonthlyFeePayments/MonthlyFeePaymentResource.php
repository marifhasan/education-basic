<?php

namespace App\Filament\Resources\MonthlyFeePayments;

use App\Filament\Resources\MonthlyFeePayments\Pages\CreateMonthlyFeePayment;
use App\Filament\Resources\MonthlyFeePayments\Pages\EditMonthlyFeePayment;
use App\Filament\Resources\MonthlyFeePayments\Pages\ListMonthlyFeePayments;
use App\Filament\Resources\MonthlyFeePayments\Pages\ViewMonthlyFeePayment;
use App\Filament\Resources\MonthlyFeePayments\Schemas\MonthlyFeePaymentForm;
use App\Filament\Resources\MonthlyFeePayments\Schemas\MonthlyFeePaymentInfolist;
use App\Filament\Resources\MonthlyFeePayments\Tables\MonthlyFeePaymentsTable;
use App\Models\MonthlyFeePayment;
use App\Services\AcademicYearContext;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonthlyFeePaymentResource extends Resource
{
    protected static ?string $model = MonthlyFeePayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function shouldRegisterNavigation(): bool
    {
        // Only show Monthly Fee Payments when an academic year is selected
        return AcademicYearContext::hasSelectedYear();
    }

    public static function form(Schema $schema): Schema
    {
        return MonthlyFeePaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MonthlyFeePaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MonthlyFeePaymentsTable::configure($table);
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
            'index' => ListMonthlyFeePayments::route('/'),
            'create' => CreateMonthlyFeePayment::route('/create'),
            'view' => ViewMonthlyFeePayment::route('/{record}'),
            'edit' => EditMonthlyFeePayment::route('/{record}/edit'),
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
