<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    public ?string $pendingStatusRecordKey = null;
    public ?string $pendingStatusNewStatus = null;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    // Filament resolves this automatically via the "{name}Action" convention
    public function confirmUpdateStatusAction(): Action
    {
        return Action::make('confirmUpdateStatus')
            ->requiresConfirmation()
            ->modalHeading('Update Invoice Status')
            ->modalDescription(fn () => $this->pendingStatusNewStatus === 'Paid' 
                ? 'Please upload payment proof to change status to "Paid".'
                : 'Are you sure you want to change the status to "' . $this->pendingStatusNewStatus . '"?')
            ->modalSubmitActionLabel('Confirm')
            ->modalCancelActionLabel('Cancel')
            ->modalWidth('md')
            ->form(fn () => $this->pendingStatusNewStatus === 'Paid' ? [
                \Filament\Forms\Components\FileUpload::make('payment_proof')
                    ->label('Foto Bukti Pembayaran')
                    ->image()
                    ->disk('public')
                    ->required()
                    ->directory('payment-proofs'),
            ] : [])
            ->action(function (array $data, \Livewire\Component $livewire) {
                if (! $this->pendingStatusRecordKey || ! $this->pendingStatusNewStatus) {
                    return;
                }

                $invoice = Invoice::find($this->pendingStatusRecordKey);
                
                $updateData = ['status' => $this->pendingStatusNewStatus];
                if ($this->pendingStatusNewStatus === 'Paid') {
                    $updateData['payment_proof'] = $data['payment_proof'] ?? null;
                }
                
                $invoice?->update($updateData);

                \Filament\Notifications\Notification::make()
                    ->title('Status updated to ' . $this->pendingStatusNewStatus)
                    ->success()
                    ->send();

                $this->pendingStatusRecordKey = null;
                $this->pendingStatusNewStatus = null;

                $livewire->redirect(static::getResource()::getUrl('index'));
            });
    }

    public function handleInvoiceStatusChange(string $recordKey, string $newStatus): void
    {
        $this->pendingStatusRecordKey = $recordKey;
        $this->pendingStatusNewStatus = $newStatus;
        $this->mountAction('confirmUpdateStatus');
    }
}
