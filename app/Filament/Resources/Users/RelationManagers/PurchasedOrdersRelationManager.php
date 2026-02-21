<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchasedOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Riwayat Pembelian';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_code')
            ->columns([
                TextColumn::make('order_code')
                    ->label('Kode Order')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('invoice_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Jml. Item')
                    ->counts('items')
                    ->alignCenter(),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'paid'      => 'info',
                        'shipped'   => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Menunggu',
                        'paid'      => 'Dibayar',
                        'shipped'   => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => $state,
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->infolist([
                        Section::make('Informasi Order')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('order_code')
                                    ->label('Kode Order'),
                                TextEntry::make('invoice_number')
                                    ->label('No. Invoice'),
                                TextEntry::make('invoice_date')
                                    ->label('Tanggal Invoice')
                                    ->date('d M Y'),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending'   => 'warning',
                                        'paid'      => 'info',
                                        'shipped'   => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default     => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'pending'   => 'Menunggu',
                                        'paid'      => 'Dibayar',
                                        'shipped'   => 'Dikirim',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default     => $state,
                                    }),
                                TextEntry::make('total_price')
                                    ->label('Total Harga')
                                    ->money('IDR'),
                                TextEntry::make('address.address')
                                    ->label('Alamat Pengiriman')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Daftar Barang')
                            ->schema([
                                RepeatableEntry::make('items')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('product_name')
                                            ->label('Nama Produk')
                                            ->weight('bold'),
                                        TextEntry::make('color_name')
                                            ->label('Warna'),
                                        TextEntry::make('size_name')
                                            ->label('Ukuran'),
                                        TextEntry::make('quantity')
                                            ->label('Qty'),
                                        TextEntry::make('price')
                                            ->label('Harga Satuan')
                                            ->money('IDR'),
                                        TextEntry::make('subtotal')
                                            ->label('Subtotal')
                                            ->money('IDR')
                                            ->weight('bold'),
                                    ])
                                    ->columns(6),
                            ]),
                    ]),
            ])
            ->headerActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
