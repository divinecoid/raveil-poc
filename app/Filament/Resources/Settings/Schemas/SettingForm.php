<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->live()
                    ->disabled(fn ($record) => $record !== null),
                
                FileUpload::make('value')
                    ->label('Video File')
                    ->visible(fn ($get) => $get('key') === 'hero_video')
                    ->disk('public')
                    ->directory('videos')
                    ->acceptedFileTypes(['video/mp4', 'video/ogg', 'video/webm'])
                    ->maxSize(102400) // 100MB max
                    ->columnSpanFull(),

                Textarea::make('value')
                    ->visible(fn ($get) => $get('key') !== 'hero_video')
                    ->columnSpanFull(),
            ]);
    }
}
