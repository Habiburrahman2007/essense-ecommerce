<?php

namespace App\Filament\Resources\Discounts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed Amount',
                    ])
                    ->required(),

                TextInput::make('value')
                    ->numeric()
                    ->required(),

                TextInput::make('max_discount')
                    ->numeric()
                    ->label('Maximum Discount Amount')
                    ->prefix('Rp'),

                TextInput::make('min_order')
                    ->numeric()
                    ->label('Minimum Order Amount')
                    ->prefix('Rp'),

                TextInput::make('usage_limit')
                    ->numeric()
                    ->label('Total Usage Limit'),

                DateTimePicker::make('expired_at')
                    ->label('Expiry Date'),
            ]);
    }
}
