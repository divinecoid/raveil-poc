<?php

namespace App\Policies;

class CategoryPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'categories';
    }
}
