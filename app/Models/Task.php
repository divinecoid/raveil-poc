<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use \App\Models\Traits\BelongsToCompany;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
