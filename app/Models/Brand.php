<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
