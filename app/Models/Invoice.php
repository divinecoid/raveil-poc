<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        $tenantSlug = $tenant ? strtoupper($tenant->slug) : 'RAV';
        $tenantPrefix = substr($tenantSlug, 0, 3);
        
        $datePrefix = now()->format('Ymd');
        $prefix = 'INV-' . $tenantPrefix . '-' . $datePrefix . '-';

        $latestInvoice = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($latestInvoice && preg_match('/-(\d{4})$/', $latestInvoice->invoice_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad((string)$sequence, 4, '0', STR_PAD_LEFT);
    }
}
