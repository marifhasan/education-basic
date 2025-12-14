<?php

namespace App\Filament\Resources\MonthlyFeePayments\Schemas;

use App\Enums\PaymentMethod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MonthlyFeePaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_monthly_fee_id')
                    ->relationship('studentMonthlyFee', 'id')
                    ->required(),
                TextInput::make('receipt_number')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->options(PaymentMethod::class)
                    ->required(),
                DatePicker::make('payment_date')
                    ->required(),
                TextInput::make('transaction_reference'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('collected_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
