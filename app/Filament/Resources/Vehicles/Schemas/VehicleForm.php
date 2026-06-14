<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('year'),
                TextInput::make('license_plate'),
            ]);
    }
}
