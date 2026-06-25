<?php

namespace App\Filament\Resources\StudioPhotos;

use App\Filament\Resources\StudioPhotos\Pages\CreateStudioPhoto;
use App\Filament\Resources\StudioPhotos\Pages\EditStudioPhoto;
use App\Filament\Resources\StudioPhotos\Pages\ListStudioPhotos;
use App\Filament\Resources\StudioPhotos\Schemas\StudioPhotoForm;
use App\Filament\Resources\StudioPhotos\Tables\StudioPhotosTable;
use App\Models\StudioPhoto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudioPhotoResource extends Resource
{
    protected static ?string $model = StudioPhoto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Studio Gallery';

    protected static ?string $pluralModelLabel = 'Studio Gallery';

    public static function form(Schema $schema): Schema
    {
        return StudioPhotoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudioPhotosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudioPhotos::route('/'),
            'create' => CreateStudioPhoto::route('/create'),
            'edit' => EditStudioPhoto::route('/{record}/edit'),
        ];
    }
}
