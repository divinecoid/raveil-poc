<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSalesResource\Pages\ListProductSales;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductSalesResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static \UnitEnum|string|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Penjualan Produk';

    protected static ?string $pluralLabel = 'Penjualan Produk';

    protected static ?string $slug = 'product-sales-report';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cost_price')
                    ->label('Harga Modal')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Harga Jual')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('total_sold')
                    ->label('Qty Terjual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_revenue')
                    ->label('Total Omset')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Total Modal')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('total_profit')
                    ->label('Profit')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->weight('bold'),
            ])
            ->recordUrl(null)
            ->recordActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select('products.*')
            ->selectSub(function ($query) {
                $query->from('sales_order_items')
                    ->selectRaw('coalesce(sum(quantity), 0)')
                    ->whereColumn('sales_order_items.product_id', 'products.id');
            }, 'total_sold')
            ->selectSub(function ($query) {
                $query->from('sales_order_items')
                    ->selectRaw('coalesce(sum(subtotal), 0)')
                    ->whereColumn('sales_order_items.product_id', 'products.id');
            }, 'total_revenue')
            ->selectSub(function ($query) {
                $query->from('sales_order_items')
                    ->selectRaw('coalesce(sum(quantity * coalesce(products.cost_price, 0)), 0)')
                    ->whereColumn('sales_order_items.product_id', 'products.id');
            }, 'total_cost')
            ->selectSub(function ($query) {
                $query->from('sales_order_items')
                    ->selectRaw('coalesce(sum(subtotal), 0) - coalesce(sum(quantity * coalesce(products.cost_price, 0)), 0)')
                    ->whereColumn('sales_order_items.product_id', 'products.id');
            }, 'total_profit');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductSales::route('/'),
        ];
    }
}
