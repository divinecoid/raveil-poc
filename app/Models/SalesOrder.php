<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function services()
    {
        return $this->hasMany(SalesOrderService::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public static function generateOrderNumber(): string
    {
        $datePrefix = now()->format('Ymd');
        $prefix = 'SO-RAV-' . $datePrefix . '-';

        $latestOrder = self::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('id', 'desc')
            ->first();

        if ($latestOrder && preg_match('/-(\d{4})$/', $latestOrder->order_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad((string)$sequence, 4, '0', STR_PAD_LEFT);
    }
}
