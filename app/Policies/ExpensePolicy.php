<?php

namespace App\Policies;

class ExpensePolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'expenses';
    }
}
