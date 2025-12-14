<?php

namespace App\Filament\Resources\StudentAcademicRecords\Tables;

use App\Enums\EnrollmentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StudentAcademicRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('roll_number')
                    ->label('Roll Number')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable(),

                TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.student_code')
                    ->label('Student Code')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('classModel.name')
                    ->label('Class')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Section')
                    ->searchable()
                    ->badge()
                    ->color(fn($record): string => $record->section->code === '0' ? 'gray' : 'primary'),

                TextColumn::make('enrollment_status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('enrollment_date')
                    ->label('Enrolled On')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('final_gpa')
                    ->label('GPA')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('final_grade')
                    ->label('Grade')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'A+', 'A' => 'success',
                        'B+', 'B' => 'info',
                        'C+', 'C' => 'warning',
                        default => 'gray',
                    })
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('class_rank')
                    ->label('Rank')
                    ->numeric()
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('promotion_date')
                    ->label('Promoted On')
                    ->date()
                    ->sortable()
                    ->placeholder('N/A')
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

                SelectFilter::make('section_id')
                    ->label('Section')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('enrollment_status')
                    ->label('Status')
                    ->options(EnrollmentStatus::class)
                    ->native(false),

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
            ->defaultSort('roll_number', 'asc');
    }
}
