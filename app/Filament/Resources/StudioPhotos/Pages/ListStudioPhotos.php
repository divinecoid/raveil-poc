<?php

namespace App\Filament\Resources\StudioPhotos\Pages;

use App\Filament\Resources\StudioPhotos\StudioPhotoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudioPhotos extends ListRecords
{
    protected static string $resource = StudioPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
