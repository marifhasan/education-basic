<?php

namespace App\Filament\Resources\Admissions\Schemas;

use App\Models\Admission;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AdmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('admission_number'),
                TextEntry::make('academicYear.name')
                    ->label('Academic year'),
                TextEntry::make('class_id')
                    ->numeric(),
                TextEntry::make('family.id')
                    ->label('Family'),
                TextEntry::make('applicant_first_name'),
                TextEntry::make('applicant_last_name'),
                TextEntry::make('applicant_gender'),
                TextEntry::make('applicant_dob')
                    ->date(),
                TextEntry::make('applicant_photo_path')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('application_date')
                    ->date(),
                TextEntry::make('approval_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rejection_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('admission_fee_amount')
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->numeric(),
                TextEntry::make('net_amount')
                    ->numeric(),
                TextEntry::make('payment_status')
                    ->badge(),
                TextEntry::make('student.id')
                    ->label('Student')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Admission $record): bool => $record->trashed()),
            ]);
    }
}
