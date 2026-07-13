<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class StudioPhoto extends Model
{
    use HasUuids;
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];
}
