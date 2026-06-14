<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_number')
                    ->required()
                    ->default(fn () => \App\Models\Invoice::generateInvoiceNumber())
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\Select::make('sales_order_id')
                    ->relationship('salesOrder', 'order_number')
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                DatePicker::make('issue_date')
                    ->default(now()),
                DatePicker::make('due_date'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'Unpaid' => 'Unpaid',
                        'Paid' => 'Paid',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('Unpaid'),
                \Filament\Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        TextInput::make('description')
                            ->required(),
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
