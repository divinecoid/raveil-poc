<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }
}
