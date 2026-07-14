<?php

namespace App\Policies;

class SupplierPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'suppliers';
    }
}
