<?php

namespace App\Filament\Resources\Sections\Tables;

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

class SectionsTable
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

                TextColumn::make('name')
                    ->label('Section Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->color(fn(string $state): string => $state === '0' ? 'gray' : 'primary')
                    ->formatStateUsing(fn(string $state): string => $state === '0' ? 'Default' : $state),

                TextColumn::make('current_strength')
                    ->label('Students')
                    ->formatStateUsing(fn($record): string => "{$record->current_strength} / {$record->capacity}")
                    ->badge()
                    ->color(function ($record): string {
                        $percentage = ($record->current_strength / $record->capacity) * 100;
                        if ($percentage >= 90) {
                            return 'danger';
                        }
                        if ($percentage >= 70) {
                            return 'warning';
                        }

                        return 'success';
                    }),

                TextColumn::make('classTeacher.name')
                    ->label('Class Teacher')
                    ->searchable()
                    ->placeholder('Not assigned'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_archived')
                    ->label('Archived')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All sections')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),

                TernaryFilter::make('is_archived')
                    ->label('Archived')
                    ->placeholder('All sections')
                    ->trueLabel('Archived only')
                    ->falseLabel('Not archived'),

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
            ->defaultSort('created_at', 'desc');
    }
}
