<?php

namespace App\Filament\Resources\MonthlyFeePayments\Schemas;

use App\Models\MonthlyFeePayment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MonthlyFeePaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('studentMonthlyFee.id')
                    ->label('Student monthly fee'),
                TextEntry::make('receipt_number'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('payment_method')
                    ->badge(),
                TextEntry::make('payment_date')
                    ->date(),
                TextEntry::make('transaction_reference')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('collected_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (MonthlyFeePayment $record): bool => $record->trashed()),
            ]);
    }
}
