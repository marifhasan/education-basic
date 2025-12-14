<?php

namespace App\Filament\Resources\Admissions\Schemas;

use App\Enums\AdmissionStatus;
use App\Enums\Gender;
use App\Enums\PaymentStatus;
use App\Models\ClassModel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class AdmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)->schema([
                    Section::make('Academic Information')
                        ->schema([
                            TextInput::make('admission_number')
                                ->label('Admission Number')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('Auto-generated')
                                ->helperText('Will be auto-generated: ADM-YYYY-0001'),

                            Select::make('academic_year_id')
                                ->relationship('academicYear', 'name', fn($query) => $query->where('is_active', true))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn(Set $set) => $set('class_id', null)),

                            Select::make('class_id')
                                ->label('Class')
                                ->options(
                                    fn(Get $get) => ClassModel::query()
                                        ->when($get('academic_year_id'), function ($query, $yearId) {
                                            $query->whereHas('academicYears', fn($q) => $q->where('academic_years.id', $yearId));
                                        })
                                        ->pluck('name', 'id')
                                )
                                ->required()
                                ->searchable()
                                ->preload()
                                ->disabled(fn(Get $get) => ! $get('academic_year_id'))
                                ->helperText('Select academic year first'),

                            DatePicker::make('application_date')
                                ->label('Application Date')
                                ->required()
                                ->default(now())
                                ->native(false),
                        ])
                        ->columns(2),

                    Section::make('Family Information')
                        ->schema([
                            Select::make('family_id')
                                ->relationship('family', 'family_code')
                                ->searchable(['family_code', 'father_name', 'father_phone'])
                                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->family_code} - {$record->father_name}")
                                ->createOptionForm([
                                    TextInput::make('father_name')->required(),
                                    TextInput::make('father_phone')->tel()->required(),
                                    TextInput::make('mother_name'),
                                    TextInput::make('address')->columnSpanFull(),
                                ])
                                ->required()
                                ->preload()
                                ->helperText('Search by family code, father name, or phone'),
                        ])
                        ->columns(1),

                    Section::make('Status & Approval')
                        ->schema([
                            Select::make('status')
                                ->options(AdmissionStatus::class)
                                ->default(AdmissionStatus::PENDING)
                                ->required()
                                ->native(false)
                                ->disabled(fn($record) => $record?->status === AdmissionStatus::ADMITTED),

                            Select::make('payment_status')
                                ->options(PaymentStatus::class)
                                ->default(PaymentStatus::UNPAID)
                                ->required()
                                ->native(false)
                                ->disabled(),

                            DatePicker::make('approval_date')
                                ->label('Approval Date')
                                ->disabled()
                                ->dehydrated()
                                ->native(false),

                            Textarea::make('rejection_reason')
                                ->label('Rejection Reason')
                                ->rows(3)
                                ->visible(fn(Get $get) => $get('status') === AdmissionStatus::REJECTED->value)
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->visible(fn($record) => $record !== null),
                ]),
                Grid::make(1)->schema([
                    Section::make('Applicant Information')
                        ->schema([
                            TextInput::make('applicant_first_name')
                                ->label('First Name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('applicant_last_name')
                                ->label('Last Name')
                                ->required()
                                ->maxLength(255),

                            Select::make('applicant_gender')
                                ->label('Gender')
                                ->options(Gender::class)
                                ->required()
                                ->native(false),

                            DatePicker::make('applicant_dob')
                                ->label('Date of Birth')
                                ->required()
                                ->native(false)
                                ->maxDate(now()->subYears(3))
                                ->helperText('Must be at least 3 years old'),

                            FileUpload::make('applicant_photo_path')
                                ->label('Photo')
                                ->image()
                                ->directory('admission-photos')
                                ->imageEditor()
                                ->maxSize(2048)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('Fee Information')
                        ->schema([
                            TextInput::make('admission_fee_amount')
                                ->label('Admission Fee')
                                ->required()
                                ->numeric()
                                ->prefix('BDT')
                                ->default(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $fee = (float) $get('admission_fee_amount') ?? 0;
                                    $discount = (float) $get('discount_amount') ?? 0;
                                    $set('net_amount', max(0, $fee - $discount));
                                }),

                            TextInput::make('discount_amount')
                                ->label('Discount Amount')
                                ->numeric()
                                ->prefix('BDT')
                                ->default(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $fee = (float) $get('admission_fee_amount') ?? 0;
                                    $discount = (float) $get('discount_amount') ?? 0;
                                    $set('net_amount', max(0, $fee - $discount));
                                }),

                            TextInput::make('net_amount')
                                ->label('Net Payable Amount')
                                ->numeric()
                                ->prefix('BDT')
                                ->disabled()
                                ->dehydrated()
                                ->default(0)
                                ->helperText('Fee Amount - Discount'),
                        ])
                        ->columns(3),

                ]),
            ]);
    }
}
