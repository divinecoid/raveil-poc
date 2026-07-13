<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderService extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'service_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
