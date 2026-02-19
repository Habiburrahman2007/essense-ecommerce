<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;    
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Product Images';

    // ⬇️ BUKAN static
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('image_url')
                ->label('Image')
                ->image()
                ->imageEditor()
                ->directory('products')
                ->disk('public') // Store in public disk
                ->required(),

            Toggle::make('is_primary')
                ->label('Primary Image')
                ->default(false),

            TextInput::make('sort_order')
                ->numeric()
                ->default(0),
        ]);
    }

    // ⬇️ BUKAN static
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->disk('public') // Read from public disk
                    ->square(),

                IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
