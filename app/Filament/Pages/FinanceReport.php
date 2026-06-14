<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FinanceReport extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';
    protected static \UnitEnum|string|null $navigationGroup = 'Finance';
    protected string $view = 'filament.pages.finance-report';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\FinanceOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\CashFlowTableWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('generateReport')
                ->label('Generate PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('start_date')
                        ->required()
                        ->default(now()->startOfMonth()),
                    \Filament\Forms\Components\DatePicker::make('end_date')
                        ->required()
                        ->default(now()->endOfMonth()),
                ])
                ->action(function (array $data) {
                    return redirect()->route('finance.pdf', [
                        'start' => $data['start_date'],
                        'end' => $data['end_date'],
                    ]);
                }),
        ];
    }
}
