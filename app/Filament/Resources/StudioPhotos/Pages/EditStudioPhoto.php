<?php

namespace App\Filament\Resources\StudioPhotos\Pages;

use App\Filament\Resources\StudioPhotos\StudioPhotoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudioPhoto extends EditRecord
{
    protected static string $resource = StudioPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
