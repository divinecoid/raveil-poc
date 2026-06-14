<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
