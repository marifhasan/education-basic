<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('designation')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('roles.name')
                    ->badge()
                    ->separator(',')
                    ->colors([
                        'danger' => 'super_admin',
                        'warning' => 'academic_coordinator',
                        'success' => 'finance_manager',
                        'info' => 'accountant',
                        'gray' => 'data_entry',
                        'primary' => 'setup_manager',
                    ])
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('last_login_at')
                    ->label('Last Login')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            // Prevent deleting super_admin users
                            $records->each(function ($record) {
                                if (!$record->hasRole('super_admin')) {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
