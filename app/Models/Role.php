<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $guarded = [];

    protected $casts = [
        'permissions' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($role) {
            if ($role->exists && strtolower($role->getOriginal('name')) === 'superadmin') {
                throw new \Exception('The Superadmin role cannot be modified.');
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
