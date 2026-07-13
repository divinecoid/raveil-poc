<?php

namespace App\Policies;

class CarModelPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'car_models';
    }
}
