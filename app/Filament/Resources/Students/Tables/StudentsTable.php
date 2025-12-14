<?php

namespace App\Filament\Resources\Students\Tables;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student_code')
                    ->label('Student Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                ImageColumn::make('photo_path')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->toggleable(),

                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->description(fn ($record) => $record->gender?->getLabel().' | '.$record->date_of_birth?->format('d M Y')),

                TextColumn::make('family.family_code')
                    ->label('Family')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => $record->family ? route('filament.admin.resources.families.families.view', $record->family) : null),

                TextColumn::make('currentAcademicRecord.classModel.name')
                    ->label('Current Class')
                    ->badge()
                    ->color('info')
                    ->default('N/A'),

                TextColumn::make('currentAcademicRecord.section.name')
                    ->label('Section')
                    ->badge()
                    ->default('N/A'),

                TextColumn::make('currentAcademicRecord.roll_number')
                    ->label('Roll')
                    ->sortable()
                    ->default('N/A'),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('blood_group')
                    ->label('Blood')
                    ->badge()
                    ->color('danger')
                    ->toggleable(),

                TextColumn::make('religion')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('admission_date')
                    ->label('Admitted')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('leaving_date')
                    ->label('Left On')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('N/A'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StudentStatus::class)
                    ->multiple()
                    ->preload(),

                SelectFilter::make('gender')
                    ->options(Gender::class)
                    ->multiple(),

                SelectFilter::make('blood_group')
                    ->label('Blood Group')
                    ->options([
                        'A+' => 'A+',
                        'A-' => 'A-',
                        'B+' => 'B+',
                        'B-' => 'B-',
                        'O+' => 'O+',
                        'O-' => 'O-',
                        'AB+' => 'AB+',
                        'AB-' => 'AB-',
                    ])
                    ->multiple(),

                SelectFilter::make('family_id')
                    ->label('Family')
                    ->relationship('family', 'family_code')
                    ->searchable()
                    ->preload(),

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
            ->defaultSort('student_code', 'asc')
            ->striped();
    }
}
