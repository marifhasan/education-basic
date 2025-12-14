<?php

namespace App\Filament\Resources\ClassFeeStructures\Tables;

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

class ClassFeeStructuresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('academicYear.name')
                    ->label('Academic Year')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('classModel.name')
                    ->label('Class')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('feeItem.name')
                    ->label('Fee Item')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('feeItem.fee_type')
                    ->label('Fee Type')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('BDT', divideBy: 1)
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
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
                SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('class_id')
                    ->label('Class')
                    ->relationship('classModel', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('fee_item_id')
                    ->label('Fee Item')
                    ->relationship('feeItem', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All structures')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),

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
            ->defaultSort('classModel.name', 'asc');
    }
}
