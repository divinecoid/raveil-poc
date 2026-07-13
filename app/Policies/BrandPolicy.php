<?php

namespace App\Policies;

class BrandPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'brands';
    }
}
