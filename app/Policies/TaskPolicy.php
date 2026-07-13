<?php

namespace App\Policies;

class TaskPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'tasks';
    }
}
