<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations\RelationManagers;

use App\Models\AdmissionFeeItem;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;

class AdmissionFeeItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'admissionFeeItems';

    protected static ?string $title = 'Fee Items';

    protected static ?string $modelLabel = 'Fee Item';

    protected static ?string $pluralModelLabel = 'Fee Items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Fee Item Details')
                    ->components([
                        TextInput::make('item_name')
                            ->label('Item Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->prefix('BDT')
                            ->required()
                            ->default(0)
                            ->step(0.01)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                // Update parent configuration total
                                $this->getOwnerRecord()->updateTotalFee();
                            }),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->columnSpan(2),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active items will be included in total calculation'),
                    ])
                    ->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_name')
            ->columns([
                TextColumn::make('item_name')
                    ->label('Item Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Fee Item'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}