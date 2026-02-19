<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $get) {
                        self::updateSku($set, $get);
                    })
                    ->searchable()
                    ->preload(),

                Select::make('size_id')
                    ->relationship('size', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $get) {
                        self::updateSku($set, $get);
                    })
                    ->searchable()
                    ->preload(),

                Select::make('color_id')
                    ->relationship('color', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($set, $get) {
                        self::updateSku($set, $get);
                    }),

                TextInput::make('sku')
                    ->label('SKU')
                    ->unique(ignoreRecord: true)
                    ->required(),

                TextInput::make('price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('stock')
                    ->numeric()
                    ->required(),
            ]);
    }

    protected static function updateSku($set, $get): void
    {
        $productId = $get('product_id');
        $sizeId = $get('size_id');
        $colorId = $get('color_id');

        if (! $productId || ! $sizeId || ! $colorId) {
            return;
        }

        $product = Product::find($productId);
        $size = Size::find($sizeId);
        $color = Color::find($colorId);

        if (! $product || ! $size || ! $color) {
            return;
        }

        $category = $product->category;
        
        // Generate base SKU: PRODUCT-slug-ID-SIZE-COLOR
        // Use Str::slug on color name to ensure URL/SKU friendliness, then upper case
        $sku = sprintf(
            '%s-%s-%s-%s', 
            Str::upper($product->slug), 
            $product->id, 
            Str::upper($size->code),
            Str::upper(Str::slug($color->name))
        );

        $set('sku', $sku);
    }
}
