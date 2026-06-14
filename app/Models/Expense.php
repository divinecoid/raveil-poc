<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use \App\Models\Traits\BelongsToCompany;

    use HasFactory;

    protected $fillable = [
        'date',
        'category',
        'amount',
        'description',
        'receipt',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
