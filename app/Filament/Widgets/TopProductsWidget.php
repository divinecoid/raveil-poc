<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TopProductsWidget extends TableWidget
{
    protected static ?string $heading = 'Top 5 Most Clicked Products';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Product::query()
                    // Default query if filter is somehow cleared
                    ->withCount(['productClicks as product_clicks_count'])
                    ->has('productClicks')
            )
            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('created_from')->label('From')->default(now()->subMonth()),
                        DatePicker::make('created_until')->label('To')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = $data['created_from'] ? Carbon::parse($data['created_from'])->startOfDay() : now()->subMonth()->startOfDay();
                        $to = $data['created_until'] ? Carbon::parse($data['created_until'])->endOfDay() : now()->endOfDay();

                        return $query->withCount(['productClicks as product_clicks_count' => function ($q) use ($from, $to) {
                            $q->whereBetween('created_at', [$from, $to]);
                        }])->whereHas('productClicks', function ($q) use ($from, $to) {
                            $q->whereBetween('created_at', [$from, $to]);
                        });
                    })
            ])
            ->modifyQueryUsing(function (Builder $query) {
                // This applies after the filter, ensuring we only show the top 5
                return $query->orderByDesc('product_clicks_count')
                             ->limit(5);
            })
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex(),
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Image')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('Category'),
                TextColumn::make('product_clicks_count')
                    ->label('Clicks')
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->paginated(false)
            ->emptyStateHeading('No clicks recorded')
            ->emptyStateDescription('No product inquiries in this date range yet.');
    }
}
