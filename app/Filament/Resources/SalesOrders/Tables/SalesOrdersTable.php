<?php

namespace App\Filament\Resources\SalesOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle.license_plate')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'Processing' => 'warning',
                        'Completed' => 'success',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->numeric()
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
                \Filament\Actions\Action::make('completeOrder')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'Completed')
                    ->action(function ($record) {
                        $record->update(['status' => 'Completed']);
                        foreach ($record->items as $item) {
                            if ($item->product) {
                                $item->product->decrement('stock_quantity', $item->quantity);
                                \App\Models\StockMovement::create([
                                    'product_id' => $item->product_id,
                                    'type' => 'out',
                                    'quantity' => $item->quantity,
                                    'reference_type' => get_class($record),
                                    'reference_id' => $record->id,
                                    'notes' => 'Sales Order Completed',
                                ]);
                            }
                        }
                        \Filament\Notifications\Notification::make()
                            ->title('Order Completed')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('generateInvoice')
                    ->label('Generate Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->visible(fn ($record) => $record->invoices()->count() === 0)
                    ->action(function ($record) {
                        $invoice = \App\Models\Invoice::create([
                            'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                            'sales_order_id' => $record->id,
                            'customer_id' => $record->customer_id,
                            'issue_date' => now(),
                            'subtotal' => $record->total_amount,
                            'total' => $record->total_amount,
                            'status' => 'Unpaid',
                        ]);
                        foreach ($record->items as $item) {
                            \App\Models\InvoiceItem::create([
                                'invoice_id' => $invoice->id,
                                'type' => 'product',
                                'description' => $item->product_name ?? ($item->product ? $item->product->name : 'Item'),
                                'quantity' => $item->quantity,
                                'unit_price' => $item->unit_price,
                                'subtotal' => $item->subtotal,
                            ]);
                        }
                        foreach ($record->services as $service) {
                            \App\Models\InvoiceItem::create([
                                'invoice_id' => $invoice->id,
                                'type' => 'service',
                                'description' => $service->service_name,
                                'quantity' => $service->quantity,
                                'unit_price' => $service->unit_price,
                                'subtotal' => $service->subtotal,
                            ]);
                        }
                        \Filament\Notifications\Notification::make()
                            ->title('Invoice Generated')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
