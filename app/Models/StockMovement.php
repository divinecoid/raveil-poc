<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }
}
