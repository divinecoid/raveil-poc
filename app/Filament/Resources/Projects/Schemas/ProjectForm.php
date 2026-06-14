<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('sales_order_id')
                    ->relationship('salesOrder', 'order_number')
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'todo' => 'Todo',
                        'in_progress' => 'In Progress',
                        'review' => 'Review',
                        'done' => 'Done',
                    ])
                    ->required()
                    ->default('todo'),
                DatePicker::make('start_date'),
                DatePicker::make('due_date'),
            ]);
    }
}
