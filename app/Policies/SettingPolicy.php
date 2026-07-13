<?php

namespace App\Policies;

class SettingPolicy extends BasePolicy
{
    protected function getResourceKey(): string
    {
        return 'settings';
    }
}
