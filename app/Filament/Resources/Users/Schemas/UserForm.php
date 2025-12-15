<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('designation')
                            ->maxLength(255),

                        TextInput::make('password')
                            ->password()
                            ->required(fn ($record) => $record === null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->maxLength(255)
                            ->helperText(fn ($record) => $record ? 'Leave blank to keep current password' : null),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive users cannot log in'),
                    ])
                    ->columns(2),

                Section::make('Roles')
                    ->schema([
                        CheckboxList::make('roles')
                            ->label('Assign Roles')
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'name'))
                            ->columns(2)
                            ->helperText('Select one or more roles for this user'),
                    ]),

                Section::make('System Information')
                    ->schema([
                        DateTimePicker::make('last_login_at')
                            ->label('Last Login')
                            ->disabled()
                            ->displayFormat('M d, Y h:i A'),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn ($record) => $record !== null),
            ]);
    }
}
