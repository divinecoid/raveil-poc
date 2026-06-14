<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use \App\Models\Traits\BelongsToCompany;

    public $table = 'cash_flows';
    
    public $timestamps = false;
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [];
    
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
