<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'order_code')
                    ->required()
                    ->searchable(),

                Select::make('gateway')
                    ->options([
                        'midtrans' => 'Midtrans',
                        'xendit' => 'Xendit',
                        'manual' => 'Manual',
                    ])
                    ->required(),

                TextInput::make('gateway_transaction_id')
                    ->label('Transaction ID'),

                TextInput::make('payment_type')
                    ->required()
                    ->placeholder('e.g. qris, va, ewallet'),

                TextInput::make('payment_method')
                    ->required()
                    ->placeholder('e.g. gopay, bca_va'),

                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('Rp'),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                    ])
                    ->default('pending')
                    ->required(),

                DateTimePicker::make('paid_at'),
            ]);
    }
}
