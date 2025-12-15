<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('phone'),
                        TextEntry::make('designation'),
                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                    ])
                    ->columns(2),

                Section::make('Roles')
                    ->schema([
                        TextEntry::make('roles.name')
                            ->badge()
                            ->separator(',')
                            ->colors([
                                'danger' => 'super_admin',
                                'warning' => 'academic_coordinator',
                                'success' => 'finance_manager',
                                'info' => 'accountant',
                                'gray' => 'data_entry',
                                'primary' => 'setup_manager',
                            ]),
                    ]),

                Section::make('System Information')
                    ->schema([
                        TextEntry::make('last_login_at')
                            ->label('Last Login')
                            ->dateTime('M d, Y h:i A')
                            ->placeholder('Never'),
                        TextEntry::make('created_at')
                            ->dateTime('M d, Y h:i A'),
                        TextEntry::make('updated_at')
                            ->dateTime('M d, Y h:i A'),
                    ])
                    ->columns(3),
            ]);
    }
}
