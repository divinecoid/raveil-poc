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
                    ->relationship('salesOrder', 'order_number', function ($query) {
                        $query->with(['customer', 'vehicle']);
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $customerName = $record->customer?->name ?? '-';
                        $vehicleModel = $record->vehicle?->model ?? '-';
                        $licensePlate = $record->vehicle?->license_plate ?? '-';
                        return "{$record->order_number} ({$customerName} - {$vehicleModel} - {$licensePlate})";
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (\Filament\Forms\Components\Select $component, string $search) {
                        $relationship = \Illuminate\Database\Eloquent\Relations\Relation::noConstraints(fn () => $component->getRelationship());

                        $query = app(\Filament\Support\Services\RelationshipJoiner::class)->prepareQueryForNoConstraints($relationship);

                        $query->with(['customer', 'vehicle']);

                        $query->where(function ($q) use ($search) {
                            $q->where('order_number', 'like', "%{$search}%")
                                ->orWhereHas('customer', function ($q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                })
                                ->orWhereHas('vehicle', function ($q) use ($search) {
                                    $q->where('license_plate', 'like', "%{$search}%")
                                        ->orWhere('model', 'like', "%{$search}%");
                                });
                        });

                        return $query->limit(50)
                            ->get()
                            ->mapWithKeys(function ($record) {
                                $customerName = $record->customer?->name ?? '-';
                                $vehicleModel = $record->vehicle?->model ?? '-';
                                $licensePlate = $record->vehicle?->license_plate ?? '-';
                                return [$record->id => "{$record->order_number} ({$customerName} - {$vehicleModel} - {$licensePlate})"];
                            })
                            ->toArray();
                    })
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if (! $state) {
                            $set('customer_id', null);
                            $set('vehicle_brand', null);
                            $set('vehicle_model', null);
                            $set('vehicle_license_plate', null);
                            return;
                        }
                        $salesOrder = \App\Models\SalesOrder::with('vehicle')->find($state);
                        if ($salesOrder) {
                            $set('customer_id', $salesOrder->customer_id);
                            if ($salesOrder->vehicle) {
                                $set('vehicle_brand', $salesOrder->vehicle->brand);
                                $set('vehicle_model', $salesOrder->vehicle->model);
                                $set('vehicle_license_plate', $salesOrder->vehicle->license_plate);
                            } else {
                                $set('vehicle_brand', null);
                                $set('vehicle_model', null);
                                $set('vehicle_license_plate', null);
                            }
                        }
                    }),
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->dehydrated()
                    ->live(),
                TextInput::make('vehicle_brand')
                    ->label('Brand Mobil')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->salesOrder?->vehicle?->brand;
                    }),
                TextInput::make('vehicle_model')
                    ->label('Tipe Mobil')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->salesOrder?->vehicle?->model;
                    }),
                TextInput::make('vehicle_license_plate')
                    ->label('Plat Mobil')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->salesOrder?->vehicle?->license_plate;
                    }),
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
