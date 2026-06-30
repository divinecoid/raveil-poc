<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('brand_id')
                    ->relationship('brand', 'name'),
                TextInput::make('car_model')
                    ->placeholder('e.g., 911 (992) GT3, Roma, M4 (G82)'),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('price_usd')
                    ->label('Price (USD)')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('cost_price')
                    ->label('Harga Modal')
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('minimum_stock')
                    ->numeric()
                    ->default(0)
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->preserveFilenames(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
