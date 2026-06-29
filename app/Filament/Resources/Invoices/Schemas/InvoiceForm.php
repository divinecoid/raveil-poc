<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function updateTotals($get, $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;
        foreach ($items as $uuid => $item) {
            $quantity = floatval($item['quantity'] ?? 0);
            $unitPrice = floatval($item['unit_price'] ?? 0);
            $itemSubtotal = $quantity * $unitPrice;
            $set("items.{$uuid}.subtotal", $itemSubtotal);
            $subtotal += $itemSubtotal;
        }
        $tax = floatval($get('tax') ?? 0);
        $set('subtotal', $subtotal);
        $set('total', $subtotal + $tax);
    }

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
                    ->default(0.0)
                    ->readOnly()
                    ->dehydrated(),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        self::updateTotals($get, $set);
                    }),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readOnly()
                    ->dehydrated(),
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
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        self::updateTotals($get, $set);
                    })
                    ->schema([
                        TextInput::make('description')
                            ->required(),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live()
                            ->columnSpan(1),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->live()
                            ->columnSpan(1),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->readOnly()
                            ->dehydrated()
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
