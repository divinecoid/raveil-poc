<?php

namespace App\Policies;

class VehiclePolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'vehicles';
    }
}
