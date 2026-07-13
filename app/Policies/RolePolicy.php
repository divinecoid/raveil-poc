<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'roles';
    }

    public function delete(User $user, $role): bool
    {
        if ($role instanceof Role && strtolower($role->name) === 'superadmin') {
            return false;
        }
        return $user->hasPermission($this->getResourceKey() . '.delete');
    }
}
