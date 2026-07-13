<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productClicks()
    {
        return $this->hasMany(ProductClick::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class);
    }
}
