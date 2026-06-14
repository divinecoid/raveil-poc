<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()),
                \Filament\Forms\Components\Select::make('category')
                    ->options([
                        'Bahan Baku' => 'Bahan Baku',
                        'Gaji Karyawan' => 'Gaji Karyawan',
                        'Operasional' => 'Operasional',
                        'Marketing' => 'Marketing',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'Paid' => 'Paid',
                        'Unpaid' => 'Unpaid',
                    ])
                    ->default('Paid')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                \Filament\Forms\Components\FileUpload::make('receipt')
                    ->image()
                    ->directory('receipts')
                    ->columnSpanFull(),
            ]);
    }
}
