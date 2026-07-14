<?php

namespace App\Filament\Resources\SalesOrders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SalesOrderForm
{
    public static function updateTotals($get, $set): void
    {
        $items = $get('items') ?? [];
        $services = $get('services') ?? [];

        $total = 0;

        foreach ($items as $uuid => $item) {
            $quantity = isset($item['quantity']) && $item['quantity'] !== '' ? floatval($item['quantity']) : 1;
            $unitPrice = floatval($item['unit_price'] ?? 0);
            $subtotal = $quantity * $unitPrice;
            $set("items.{$uuid}.subtotal", $subtotal);
            $total += $subtotal;
        }

        foreach ($services as $uuid => $service) {
            $quantity = isset($service['quantity']) && $service['quantity'] !== '' ? floatval($service['quantity']) : 1;
            $unitPrice = floatval($service['unit_price'] ?? 0);
            $subtotal = $quantity * $unitPrice;
            $set("services.{$uuid}.subtotal", $subtotal);
            $total += $subtotal;
        }

        $set('total_amount', $total);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required()
                    ->default(fn() => \App\Models\SalesOrder::generateOrderNumber())
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $set('vehicle_id', null);
                        $set('vehicle_brand', null);
                        $set('vehicle_model', null);
                    })
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        \Filament\Forms\Components\Textarea::make('address')
                            ->columnSpanFull(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $data['company_id'] = \Filament\Facades\Filament::getTenant()->id;
                        $customer = \App\Models\Customer::create($data);
                        return $customer->id;
                    }),
                \Filament\Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'license_plate', function ($query, $get) {
                        $customerId = $get('customer_id');
                        if ($customerId) {
                            $query->where('customer_id', $customerId);
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->license_plate} ({$record->brand} {$record->model})")
                    ->searchable()
                    ->preload()
                    ->live()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('license_plate')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('brand')
                            ->options(fn() => \App\Models\Brand::pluck('name', 'name')->toArray())
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
                        \Filament\Forms\Components\TextInput::make('year')
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data, $get, $set, $livewire) {
                        \Illuminate\Support\Facades\Log::info('Paths check:', [
                            'direct' => $get('customer_id'),
                            'one_level_up' => $get('../customer_id'),
                            'two_levels_up' => $get('../../customer_id'),
                            'three_levels_up' => $get('../../../customer_id'),
                            'livewire_data' => isset($livewire->data) ? $livewire->data : null,
                        ]);

                        try {
                            $customerId = $get('../../customer_id') ?: ($livewire->data['customer_id'] ?? null);
                            if (!$customerId) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Customer Belum Dipilih')
                                    ->body('Silakan pilih customer terlebih dahulu sebelum menambahkan kendaraan.')
                                    ->danger()
                                    ->send();
                                return null;
                            }
                            $data['customer_id'] = $customerId;
                            $data['company_id'] = \Filament\Facades\Filament::getTenant()->id;
                            $vehicle = \App\Models\Vehicle::create($data);
                            $set('vehicle_brand', $vehicle->brand);
                            $set('vehicle_model', $vehicle->model);
                            return $vehicle->getKey();
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Vehicle Create Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                            throw $e;
                        }
                    })
                    ->afterStateUpdated(function ($state, $set) {
                        if (!$state) {
                            $set('vehicle_brand', null);
                            $set('vehicle_model', null);
                            return;
                        }
                        $vehicle = \App\Models\Vehicle::find($state);
                        if ($vehicle) {
                            $set('vehicle_brand', $vehicle->brand);
                            $set('vehicle_model', $vehicle->model);
                        }
                    }),
                TextInput::make('vehicle_brand')
                    ->label('Brand Mobil')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->vehicle?->brand;
                    }),
                TextInput::make('vehicle_model')
                    ->label('Tipe Mobil')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->vehicle?->model;
                    }),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('Pending'),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readOnly()
                    ->dehydrated(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        self::updateTotals($get, $set);
                    })
                    ->schema([
                        \Filament\Forms\Components\Select::make('product_name')
                            ->options(function ($get) {
                                $query = \App\Models\Product::query();
                                $vehicleId = $get('../../vehicle_id');
                                $state = $get('product_name');
                                if ($vehicleId) {
                                    $vehicle = \App\Models\Vehicle::find($vehicleId);
                                    if ($vehicle) {
                                        $query->where(function ($q) use ($vehicle, $state) {
                                            $q->whereHas('brand', function ($brandQuery) use ($vehicle) {
                                                $brandQuery->whereRaw('LOWER(name) = ?', [strtolower(trim($vehicle->brand))]);
                                            })->whereRaw('LOWER(car_model) = ?', [strtolower(trim($vehicle->model))]);

                                            if ($state) {
                                                $q->orWhere('name', $state);
                                            }
                                        });
                                    }
                                }
                                return $query->pluck('name', 'name');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->createOptionForm([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\Select::make('category_id')
                                    ->label('Category')
                                    ->options(fn() => \App\Models\Category::pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        \Filament\Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        $data['company_id'] = \Filament\Facades\Filament::getTenant()->id;
                                        $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . uniqid();
                                        $category = \App\Models\Category::create($data);
                                        return $category->id;
                                    }),
                                \Filament\Forms\Components\Select::make('brand_id')
                                    ->label('Brand')
                                    ->options(fn() => \App\Models\Brand::pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($set) {
                                        $set('car_model', null);
                                    })
                                    ->default(function ($livewire) {
                                        $vehicleId = $livewire->data['vehicle_id'] ?? null;
                                        if ($vehicleId) {
                                            $vehicle = \App\Models\Vehicle::find($vehicleId);
                                            if ($vehicle) {
                                                $brand = \App\Models\Brand::whereRaw('LOWER(name) = ?', [strtolower(trim($vehicle->brand))])->first();
                                                return $brand?->id;
                                            }
                                        }
                                        return null;
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
                                        return $brand->id;
                                    }),
                                \Filament\Forms\Components\Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->options(fn() => \App\Models\Supplier::pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        \Filament\Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        \Filament\Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(255),
                                        \Filament\Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->maxLength(255),
                                        \Filament\Forms\Components\Textarea::make('address')
                                            ->columnSpanFull(),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        $data['company_id'] = \Filament\Facades\Filament::getTenant()->id;
                                        $supplier = \App\Models\Supplier::create($data);
                                        return $supplier->id;
                                    }),
                                \Filament\Forms\Components\Select::make('car_model')
                                    ->label('Car Model')
                                    ->options(function ($get) {
                                        $brandId = $get('brand_id');
                                        $query = \App\Models\CarModel::query();
                                        if ($brandId) {
                                            $query->where('brand_id', $brandId);
                                        }
                                        return $query->pluck('name', 'name')->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->default(function ($livewire) {
                                        $vehicleId = $livewire->data['vehicle_id'] ?? null;
                                        if ($vehicleId) {
                                            $vehicle = \App\Models\Vehicle::find($vehicleId);
                                            return $vehicle?->model;
                                        }
                                        return null;
                                    })
                                    ->createOptionForm([
                                        \Filament\Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data, $get) {
                                        $brandId = $get('brand_id');
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
                                \Filament\Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->label('Price'),
                                \Filament\Forms\Components\TextInput::make('cost_price')
                                    ->numeric()
                                    ->label('Harga Modal'),
                                \Filament\Forms\Components\Textarea::make('description')
                                    ->columnSpanFull(),
                            ])
                            ->createOptionUsing(function (array $data, $get) {
                                $data['company_id'] = \Filament\Facades\Filament::getTenant()->id;
                                $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . uniqid();

                                $vehicleId = $get('../../vehicle_id');
                                if ($vehicleId) {
                                    $vehicle = \App\Models\Vehicle::find($vehicleId);
                                    if ($vehicle) {
                                        if (empty($data['brand_id'])) {
                                            $brand = \App\Models\Brand::whereRaw('LOWER(name) = ?', [strtolower(trim($vehicle->brand))])->first();
                                            if ($brand) {
                                                $data['brand_id'] = $brand->id;
                                            }
                                        }
                                        if (empty($data['car_model'])) {
                                            $data['car_model'] = $vehicle->model;
                                        }
                                    }
                                }
                                $product = \App\Models\Product::create($data);
                                return $product->name;
                            })
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($state) {
                                    $product = \App\Models\Product::where('name', $state)->first();
                                    if ($product) {
                                        $set('product_id', $product->id);
                                        $set('unit_price', $product->price ?? 0);
                                    }
                                } else {
                                    $set('product_id', null);
                                    $set('unit_price', 0);
                                }
                                self::updateTotals(
                                    fn($path) => $get("../../{$path}"),
                                    fn($path, $value) => $set("../../{$path}", $value)
                                );
                            })
                            ->columnSpan(2),
                        \Filament\Forms\Components\Hidden::make('product_id'),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::updateTotals(
                                    fn($path) => $get("../../{$path}"),
                                    fn($path, $value) => $set("../../{$path}", $value)
                                );
                            })
                            ->columnSpan(1),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::updateTotals(
                                    fn($path) => $get("../../{$path}"),
                                    fn($path, $value) => $set("../../{$path}", $value)
                                );
                            })
                            ->columnSpan(1),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->readOnly()
                            ->dehydrated()
                            ->columnSpan(1),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),
                \Filament\Forms\Components\Repeater::make('services')
                    ->relationship()
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        self::updateTotals($get, $set);
                    })
                    ->schema([
                        TextInput::make('service_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::updateTotals(
                                    fn($path) => $get("../../{$path}"),
                                    fn($path, $value) => $set("../../{$path}", $value)
                                );
                            }),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::updateTotals(
                                    fn($path) => $get("../../{$path}"),
                                    fn($path, $value) => $set("../../{$path}", $value)
                                );
                            }),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->readOnly()
                            ->dehydrated(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
