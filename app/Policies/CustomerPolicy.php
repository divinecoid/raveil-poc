<?php

namespace App\Policies;

class CustomerPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'customers';
    }
}
