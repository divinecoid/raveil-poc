<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $product = $item->product;
            if ($product && floatval($product->cost_price) > 0) {
                $costPrice = floatval($product->cost_price);
                $totalCost = $costPrice * $item->quantity;
                
                // Get order number for description
                $orderNumber = $item->salesOrder?->order_number ?? 'N/A';
                
                \App\Models\Expense::create([
                    'company_id' => $item->company_id ?? $item->salesOrder?->company_id,
                    'date' => now(),
                    'category' => 'Harga Modal',
                    'amount' => $totalCost,
                    'description' => 'Harga Modal untuk ' . $product->name . ' (x' . $item->quantity . ') - Sales Order: ' . $orderNumber,
                    'status' => 'Paid',
                ]);
            }
        });
    }
}
