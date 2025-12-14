<?php

namespace App\Filament\Resources\StudentAcademicRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StudentAcademicRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.id')
                    ->searchable(),
                TextColumn::make('academicYear.name')
                    ->searchable(),
                TextColumn::make('class_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('section.name')
                    ->searchable(),
                TextColumn::make('roll_number')
                    ->searchable(),
                TextColumn::make('enrollment_status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('enrollment_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('promotion_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('final_percentage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('final_gpa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('final_grade')
                    ->searchable(),
                TextColumn::make('class_rank')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
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
            ]);
    }
}
