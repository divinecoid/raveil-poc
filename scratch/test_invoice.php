<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Invoice;
use App\Models\Company;
use Livewire\Livewire;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use Filament\Facades\Filament;

$user = User::first();
if (!$user) {
    echo "No user found!\n";
    exit(1);
}

// Log in as user
auth()->login($user);
$panel = Filament::getPanel('admin');
Filament::setCurrentPanel($panel);

$company = Company::find(1) ?? Company::first();
if ($company) {
    Filament::setTenant($company);
} else {
    echo "No company found!\n";
    exit(1);
}

// Let's get an invoice
$invoice = Invoice::first();
if (!$invoice) {
    echo "No invoice found!\n";
    exit(1);
}

try {
    $testable = Livewire::test(ListInvoices::class, [
        'tenant' => $company->id,
    ]);

    $invoice->update(['status' => 'Unpaid']);
    echo "Before: " . $invoice->fresh()->status . "\n";

    $testable->callTableAction('updateStatus', $invoice, data: ['status' => 'Paid']);

    echo "After: " . $invoice->fresh()->status . "\n";
    echo "Test PASSED!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
