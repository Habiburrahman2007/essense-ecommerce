<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),

                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->live()
                    ->searchable()
                    ->preload(),

                TextInput::make('base_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                RichEditor::make('description')
                    ->columnSpanFull(),

                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),

                Repeater::make('images')
                    ->relationship()
                    ->schema([
                        FileUpload::make('image_url')
                            ->label('Image')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('products'),
                        Toggle::make('is_primary')
                            ->default(false),
                    ])
                    ->grid(2)
                    ->columnSpanFull(),


            ]);
    }
}
