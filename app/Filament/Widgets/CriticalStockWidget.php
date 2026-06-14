<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CriticalStockWidget extends TableWidget
{
    protected static ?string $heading = 'Critical Stock Products';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 10;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => \App\Models\Product::query()->whereColumn('stock_quantity', '<=', 'minimum_stock'))
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Current Stock')
                    ->badge()
                    ->color('danger')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('minimum_stock')
                    ->label('Minimum Required')
                    ->sortable(),
            ]);
    }
}
