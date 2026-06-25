<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('salesOrder.vehicle.license_plate')
                    ->label('License Plate')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('salesOrder.vehicle.brand')
                    ->label('Vehicle')
                    ->formatStateUsing(fn ($state, $record) => $record->salesOrder?->vehicle ? "{$record->salesOrder->vehicle->brand} {$record->salesOrder->vehicle->model}" : '-')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('salesOrder.invoices.invoice_number')
                    ->label('Invoice Number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'todo' => 'gray',
                        'in_progress' => 'warning',
                        'review' => 'info',
                        'done' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'todo' => 'Todo',
                        'in_progress' => 'In Progress',
                        'review' => 'Review',
                        'done' => 'Done',
                        default => $state,
                    })
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
