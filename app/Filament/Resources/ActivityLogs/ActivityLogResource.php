<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static UnitEnum|string|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?string $modelLabel = 'Activity Log';

    protected static ?string $pluralModelLabel = 'Activity Logs';

    public static function canAccess(): bool
    {
        // Only users with view_activity_logs permission or super_admin/finance_manager/academic_coordinator
        return Gate::allows('view_activity_logs') || auth()->user()?->hasAnyRole(['super_admin', 'finance_manager', 'academic_coordinator']);
    }

    public static function canCreate(): bool
    {
        return false; // Activity logs are read-only
    }

    public static function canEdit($record): bool
    {
        return false; // Activity logs are read-only
    }

    public static function canDelete($record): bool
    {
        return false; // Activity logs are read-only
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->searchable(),

                TextColumn::make('causer.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->default('System'),

                TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return '-';
                        }
                        $attributes = $state['attributes'] ?? [];
                        $old = $state['old'] ?? [];

                        if (empty($attributes) && empty($old)) {
                            return '-';
                        }

                        $changes = [];
                        foreach ($attributes as $key => $value) {
                            if (isset($old[$key]) && $old[$key] != $value) {
                                $changes[] = "$key: {$old[$key]} → $value";
                            } elseif (!isset($old[$key])) {
                                $changes[] = "$key: $value";
                            }
                        }

                        return implode(', ', $changes) ?: '-';
                    })
                    ->limit(60)
                    ->tooltip(function ($record) {
                        return json_encode($record->properties, JSON_PRETTY_PRINT);
                    }),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->options([
                        'default' => 'Default',
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),

                SelectFilter::make('causer_id')
                    ->label('User')
                    ->relationship('causer', 'name'),
            ])
            ->actions([
                // No actions - read-only
            ])
            ->bulkActions([
                // No bulk actions - read-only
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['causer', 'subject']);
    }
}
