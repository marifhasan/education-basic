<?php

namespace App\Filament\Resources\Admissions\Tables;

use App\Enums\AdmissionStatus;
use App\Enums\PaymentStatus;
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

class AdmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('admission_number')
                    ->label('Admission #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                ImageColumn::make('applicant_photo_path')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->toggleable(),

                TextColumn::make('applicant_full_name')
                    ->label('Applicant Name')
                    ->getStateUsing(fn ($record) => $record->applicant_first_name.' '.$record->applicant_last_name)
                    ->searchable(['applicant_first_name', 'applicant_last_name'])
                    ->sortable()
                    ->description(fn ($record) => $record->applicant_gender?->getLabel().' | '.$record->applicant_dob?->format('d M Y')),

                TextColumn::make('family.family_code')
                    ->label('Family Code')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => $record->family ? route('filament.admin.resources.families.families.view', $record->family) : null),

                TextColumn::make('classModel.name')
                    ->label('Class')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->sortable(),

                TextColumn::make('net_amount')
                    ->label('Net Amount')
                    ->money('BDT')
                    ->sortable()
                    ->alignEnd()
                    ->description(fn ($record) => $record->discount_amount > 0
                        ? 'Discount: '.number_format($record->discount_amount, 2)
                        : null),

                TextColumn::make('application_date')
                    ->label('Applied On')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('approval_date')
                    ->label('Approved On')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('N/A'),

                TextColumn::make('student.student_code')
                    ->label('Student Code')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Not Admitted')
                    ->url(fn ($record) => $record->student ? route('filament.admin.resources.students.students.view', $record->student) : null),

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
                    ->options(AdmissionStatus::class)
                    ->multiple()
                    ->preload(),

                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options(PaymentStatus::class)
                    ->multiple()
                    ->preload(),

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
