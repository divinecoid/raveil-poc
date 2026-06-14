<?php

namespace App\Filament\Widgets;

use App\Models\CashFlow;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class CashFlowTableWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 11;
    
    protected static ?string $heading = 'Combined Income & Expense';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CashFlow::query()
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Income' => 'success',
                        'Expense' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->label('Reference (Invoice / Category)'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->color(fn ($record) => $record->type === 'Income' ? 'success' : 'danger'),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('date_from'),
                        DatePicker::make('date_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->defaultSort('date', 'desc');
    }
}
