<?php

namespace App\Policies;

class UserPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'users';
    }
}
