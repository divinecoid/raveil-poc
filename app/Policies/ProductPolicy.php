<?php

namespace App\Policies;

class ProductPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'products';
    }
}
