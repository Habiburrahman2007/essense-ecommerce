<?php

namespace App\Filament\Resources\Discounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;

class DiscountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'primary',
                        'fixed' => 'success',
                    }),
                TextColumn::make('value')
                    ->formatStateUsing(fn (string $state, $record): string => $record->type === 'fixed' ? 'Rp ' . number_format($state, 0, ',', '.') : $state . '%')
                    ->sortable(),
                TextColumn::make('used_count')
                    ->label('Usage')
                    ->sortable(),
                TextColumn::make('usage_limit')
                    ->label('Limit'),
                TextColumn::make('expired_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])


            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
