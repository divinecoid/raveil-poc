<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('brand')
                    ->options(fn () => \App\Models\Brand::pluck('name', 'name')->toArray())
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set) {
                        $set('model', null);
                    })
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $data['company_id'] = \Filament\Facades\Filament::getTenant()->id;
                        $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . uniqid();
                        $brand = \App\Models\Brand::create($data);
                        return $brand->name;
                    }),
                \Filament\Forms\Components\Select::make('model')
                    ->options(function ($get) {
                        $brandName = $get('brand');
                        $query = \App\Models\CarModel::query();
                        
                        if ($brandName) {
                            $query->whereHas('brand', function ($q) use ($brandName) {
                                $q->whereRaw('LOWER(name) = ?', [strtolower(trim($brandName))]);
                            });
                        }
                        
                        return $query->pluck('name', 'name')->toArray();
                    })
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data, $get) {
                        $brandName = $get('brand');
                        $brandId = null;
                        if ($brandName) {
                            $brand = \App\Models\Brand::whereRaw('LOWER(name) = ?', [strtolower(trim($brandName))])->first();
                            if ($brand) {
                                $brandId = $brand->id;
                            }
                        }
                        
                        $companyId = \Filament\Facades\Filament::getTenant()->id;
                        $slug = \Illuminate\Support\Str::slug($data['name']) . '-' . uniqid();
                        
                        $carModel = \App\Models\CarModel::create([
                            'company_id' => $companyId,
                            'brand_id' => $brandId,
                            'name' => $data['name'],
                            'slug' => $slug,
                        ]);
                        
                        return $carModel->name;
                    }),
                TextInput::make('year'),
                TextInput::make('license_plate'),
            ]);
    }
}
