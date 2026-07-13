<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductClick extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    public $timestamps = false;

    protected $fillable = ['product_id', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
