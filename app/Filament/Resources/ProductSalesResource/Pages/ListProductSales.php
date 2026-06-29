<?php

namespace App\Filament\Resources\ProductSalesResource\Pages;

use App\Filament\Resources\ProductSalesResource;
use Filament\Resources\Pages\ListRecords;

class ListProductSales extends ListRecords
{
    protected static string $resource = ProductSalesResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
