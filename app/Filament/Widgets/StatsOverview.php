<?php

namespace App\Filament\Widgets;

use App\Models\Setting;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $visitorCount = Setting::where('key', 'visitor_count')->value('value') ?? '0';
        $whatsappClicks = Setting::where('key', 'whatsapp_click_count')->value('value') ?? '0';

        $mostClickedProduct = \App\Models\Product::where('clicks', '>', 0)->orderByDesc('clicks')->first();
        
        if ($mostClickedProduct) {
            $popularTitle = $mostClickedProduct->name;
            $popularDesc = number_format($mostClickedProduct->clicks) . ' inquires';
        } else {
            $popularTitle = 'None';
            $popularDesc = 'No catalog inquires yet';
        }

        return [
            Stat::make('Total Visitors', number_format((int) $visitorCount))
                ->description('Unique session visitors')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('WhatsApp Floating Clicks', number_format((int) $whatsappClicks))
                ->description('Clicks on floating WhatsApp button')
                ->descriptionIcon('heroicon-m-phone')
                ->color('success'),
            Stat::make('Most Popular Item', $popularTitle)
                ->description($popularDesc)
                ->descriptionIcon('heroicon-m-fire')
                ->color('success'),
        ];
    }
}
