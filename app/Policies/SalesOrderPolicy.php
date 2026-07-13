<?php

namespace App\Policies;

class SalesOrderPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'sales_orders';
    }
}
