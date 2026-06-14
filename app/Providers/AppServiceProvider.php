<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Filament\Tables\Columns\Column;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Column::configureUsing(function (Column $column): void {
            $column->toggleable();
        });
    }
}
