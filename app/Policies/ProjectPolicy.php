<?php

namespace App\Policies;

class ProjectPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'projects';
    }
}
