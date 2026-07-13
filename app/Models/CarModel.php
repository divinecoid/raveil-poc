<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
