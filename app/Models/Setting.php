<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];
}
