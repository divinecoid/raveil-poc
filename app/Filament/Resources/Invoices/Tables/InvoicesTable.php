<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('salesOrder.order_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('issue_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Unpaid' => 'danger',
                        'Paid' => 'success',
                        'Cancelled' => 'gray',
                        default => 'primary',
                    })
                    ->action(
                        \Filament\Actions\Action::make('updateStatus')
                            ->schema([
                                \Filament\Forms\Components\Select::make('status')
                                    ->options([
                                        'Unpaid' => 'Unpaid',
                                        'Paid' => 'Paid',
                                        'Cancelled' => 'Cancelled',
                                    ])
                                    ->required(),
                            ])
                            ->requiresConfirmation()
                            ->modalHeading('Update Status')
                            ->modalDescription('Are you sure you would like to do this?')
                            ->modalSubmitActionLabel('Confirm')
                            ->modalCancelActionLabel('Cancel')
                            ->fillForm(fn ($record) => ['status' => $record->status])
                            ->action(function (array $data, $record): void {
                                $record->update(['status' => $data['status']]);

                                \Filament\Notifications\Notification::make()
                                    ->title('Status Updated')
                                    ->success()
                                    ->send();
                            })
                    )
                    ->searchable(),
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
                \Filament\Actions\Action::make('downloadPdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['invoice' => $record]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, $record->invoice_number . '.pdf');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
