<?php

namespace App\Filament\Resources\SalesOrders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SalesOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required()
                    ->default(fn () => \App\Models\SalesOrder::generateOrderNumber())
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->live(),
                \Filament\Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'license_plate', function ($query, $get) {
                        $customerId = $get('customer_id');
                        if ($customerId) {
                            $query->where('customer_id', $customerId);
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->license_plate} ({$record->brand} {$record->model})")
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('license_plate')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('brand')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('model')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('year')
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data, $get) {
                        $customerId = $get('customer_id');
                        if (! $customerId) {
                            throw new \Illuminate\Validation\ValidationException(
                                \Illuminate\Support\Facades\Validator::make([], [])->errors()->add('vehicle_id', 'Please select a customer first before creating a vehicle.')
                            );
                        }
                        $data['customer_id'] = $customerId;
                        return \App\Models\Vehicle::create($data)->getKey();
                    }),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('Pending'),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('notes')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('product_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                \Filament\Forms\Components\Repeater::make('services')
                    ->relationship()
                    ->schema([
                        TextInput::make('service_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
