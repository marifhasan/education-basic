<?php

namespace App\Filament\Resources\Families\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FamiliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('family_code')
                    ->label('Family Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('father_name')
                    ->label('Father Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->father_phone),

                TextColumn::make('mother_name')
                    ->label('Mother Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->mother_phone)
                    ->toggleable(),

                TextColumn::make('primary_phone')
                    ->label('Primary Contact')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                TextColumn::make('present_address')
                    ->label('Address')
                    ->limit(50)
                    ->toggleable()
                    ->wrap(),

                TextColumn::make('students_count')
                    ->label('Students')
                    ->counts('students')
                    ->badge()
                    ->color('success'),

                TextColumn::make('father_income')
                    ->label('Family Income')
                    ->money('BDT')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('guardian_name')
                    ->label('Guardian')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('N/A'),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}
