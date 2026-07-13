<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'email', 'password', 'permissions', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasTenants
{
    use HasUuids;
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission(string $permission): bool
    {
        // Check role permissions first if a role is assigned
        if ($this->role) {
            if ($this->role->permissions === null) {
                return true;
            }
            return (bool) \Illuminate\Support\Arr::get($this->role->permissions, $permission, false);
        }

        // Fallback to direct user permissions if no role is assigned
        if ($this->permissions === null) {
            return true;
        }

        if (!is_array($this->permissions)) {
            return false;
        }
        return (bool) \Illuminate\Support\Arr::get($this->permissions, $permission, false);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->companies;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->companies()->whereKey($tenant)->exists();
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestTenant();
    }

    public function latestTenant()
    {
        return $this->companies()->first();
    }
}
