<?php

namespace App\Policies;

use App\Models\User;

abstract class BasePolicy
{
    /**
     * Get the resource key (e.g. 'sales_orders', 'products').
     */
    abstract protected function getResourceKey(): string;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission($this->getResourceKey() . '.view');
    }

    public function view(User $user, $model): bool
    {
        return $user->hasPermission($this->getResourceKey() . '.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission($this->getResourceKey() . '.create');
    }

    public function update(User $user, $model): bool
    {
        return $user->hasPermission($this->getResourceKey() . '.edit');
    }

    public function delete(User $user, $model): bool
    {
        return $user->hasPermission($this->getResourceKey() . '.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission($this->getResourceKey() . '.delete');
    }
}
