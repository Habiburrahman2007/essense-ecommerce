<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('address_id')
                    ->relationship('address', 'address')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('order_code')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('invoice_number')
                    ->required()
                    ->unique(ignoreRecord: true),

                DatePicker::make('invoice_date')
                    ->required(),

                TextInput::make('total_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
