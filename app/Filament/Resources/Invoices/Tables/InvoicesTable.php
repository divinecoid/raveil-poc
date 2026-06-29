<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
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
                ViewColumn::make('status')
                    ->view('filament.tables.columns.invoice-status-select')
                    ->searchable(),
                \Filament\Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(null)
                    ->action(
                        \Filament\Actions\Action::make('viewPaymentProof')
                            ->modalHeading('Bukti Pembayaran')
                            ->modalContent(fn ($record) => $record->payment_proof ? new \Illuminate\Support\HtmlString(
                                '<div style="display: flex; justify-content: center; align-items: center; padding: 10px;"><img src="' . \Illuminate\Support\Facades\Storage::disk('public')->url($record->payment_proof) . '" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);" /></div>'
                            ) : null)
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                    ),
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
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['invoice' => $record->load('items', 'customer', 'salesOrder')])
                            ->setPaper('a4', 'portrait')
                            ->setOptions(array_merge(
                                config('dompdf.options') ?? [],
                                [
                                    'isHtml5ParserEnabled' => true,
                                    'isRemoteEnabled'      => true,
                                    'defaultFont'          => 'DejaVu Sans',
                                    'dpi'                  => 150,
                                ]
                            ));
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
