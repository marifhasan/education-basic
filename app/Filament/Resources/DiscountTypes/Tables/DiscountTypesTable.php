<?php

namespace App\Filament\Resources\DiscountTypes\Tables;

use App\Enums\DiscountCalculationType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DiscountTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Discount Name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('calculation_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('default_value')
                    ->label('Default Value')
                    ->formatStateUsing(fn($record): string => $record->calculation_type === DiscountCalculationType::PERCENTAGE
                        ? "{$record->default_value}%"
                        : number_format($record->default_value, 2).' BDT'
                    )
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(50)
                    ->placeholder('No description')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('requires_approval')
                    ->label('Needs Approval')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('calculation_type')
                    ->label('Calculation Type')
                    ->options(DiscountCalculationType::class)
                    ->native(false),

                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All discount types')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),

                TernaryFilter::make('requires_approval')
                    ->label('Approval Required')
                    ->placeholder('All discount types')
                    ->trueLabel('Requires approval')
                    ->falseLabel('No approval needed'),

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
            ->defaultSort('name', 'asc');
    }
}
