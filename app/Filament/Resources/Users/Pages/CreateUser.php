<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        if ($tenant) {
            $this->record->companies()->attach($tenant);
        }
    }
}
