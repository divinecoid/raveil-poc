<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $income = \App\Models\Invoice::where('status', 'Paid')->sum('total');
        $expense = \App\Models\Expense::where('status', 'Paid')->sum('amount');
        $netProfit = $income - $expense;

        return [
            Stat::make('Total Income (Paid Invoices)', 'Rp ' . number_format($income, 0, ',', '.'))
                ->description('All revenue from paid invoices')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Expenses', 'Rp ' . number_format($expense, 0, ',', '.'))
                ->description('All recorded expenditures')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Net Profit', 'Rp ' . number_format($netProfit, 0, ',', '.'))
                ->description('Income minus Expenses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($netProfit >= 0 ? 'success' : 'danger'),
        ];
    }
}
