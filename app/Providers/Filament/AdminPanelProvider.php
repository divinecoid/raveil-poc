<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('RAVEIL')
            ->darkMode(true, true)
            ->font('Inter')
            ->renderHook(
                'panels::head.end',
                fn (): \Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('
                    <style>
                        /* Sleek Premium Dark Mode Overrides for Login */
                        body.fi-body {
                            background-color: #030303 !important;
                            color: #f0f0f0 !important;
                        }
                        .fi-simple-layout {
                            background-color: #030303 !important;
                            background-image: 
                                radial-gradient(at 0% 0%, rgba(255,255,255,0.03) 0, transparent 50%), 
                                radial-gradient(at 50% 0%, rgba(255,255,255,0.015) 0, transparent 50%),
                                radial-gradient(at 100% 0%, rgba(255,255,255,0.03) 0, transparent 50%) !important;
                        }
                        .fi-simple-main-ctn {
                            background: transparent !important;
                        }
                        .fi-simple-card {
                            background-color: rgba(13, 13, 13, 0.75) !important;
                            backdrop-filter: blur(16px) !important;
                            -webkit-backdrop-filter: blur(16px) !important;
                            border: 1px solid rgba(255, 255, 255, 0.06) !important;
                            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.65) !important;
                            border-radius: 1.25rem !important;
                            padding: 2.5rem 2rem !important;
                        }
                        .fi-logo, .fi-simple-header-heading {
                            font-family: "Inter", sans-serif !important;
                            font-weight: 900 !important;
                            letter-spacing: 0.35em !important;
                            text-transform: uppercase !important;
                            color: #ffffff !important;
                            text-align: center !important;
                            display: block !important;
                            font-size: 1.8rem !important;
                            text-shadow: 0 0 20px rgba(255, 255, 255, 0.15) !important;
                            margin-bottom: 0.5rem !important;
                        }
                        /* Hide default subheadings */
                        .fi-simple-header-subheading {
                            color: #8a8a8a !important;
                            font-weight: 300 !important;
                        }
                        /* Form fields styling */
                        .fi-simple-card input {
                            background-color: rgba(255, 255, 255, 0.02) !important;
                            border: 1px solid rgba(255, 255, 255, 0.08) !important;
                            color: #ffffff !important;
                            border-radius: 0.375rem !important;
                            transition: all 0.3s ease !important;
                        }
                        .fi-simple-card input:focus {
                            border-color: rgba(255, 255, 255, 0.3) !important;
                            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.05) !important;
                        }
                        .fi-simple-card label {
                            color: #8a8a8a !important;
                            font-size: 0.75rem !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.05em !important;
                        }
                        /* Primary Sign-In Button */
                        .fi-simple-card button[type="submit"] {
                            background-color: #ffffff !important;
                            color: #000000 !important;
                            font-weight: 700 !important;
                            letter-spacing: 0.1em !important;
                            text-transform: uppercase !important;
                            transition: all 0.3s ease !important;
                            border-radius: 0.375rem !important;
                            border: none !important;
                            padding: 0.75rem !important;
                            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1) !important;
                        }
                        .fi-simple-card button[type="submit"]:hover {
                            background-color: #e4e4e7 !important;
                            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.25) !important;
                            transform: translateY(-1px);
                        }
                    </style>
                ')
            )
            ->renderHook(
                'panels::auth.login.form.after',
                fn (): \Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('
                    <div class="demo-creds-box" style="margin-top: 1.5rem; padding: 1rem; background-color: rgba(255, 255, 255, 0.01); border-radius: 0.5rem; text-align: center; border: 1px solid rgba(255,255,255,0.04);">
                        <p style="font-size: 0.75rem; color: #8a8a8a; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;"><strong>Demo Credentials</strong></p>
                        <p style="font-size: 0.875rem; color: #f0f0f0;">Email: <span style="font-family: monospace;">admin@admin.com</span></p>
                        <p style="font-size: 0.875rem; color: #f0f0f0;">Password: <span style="font-family: monospace;">password</span></p>
                    </div>
                ')
            )
            ->colors([
                'primary' => Color::Zinc,
                'gray' => Color::Zinc,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->navigationGroups([
                'Transaksi',
                'Finance',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
