<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Navigation\NavigationItem;
// PLUGIN
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;

class DKVPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('dKV')
            ->path('dkv')
            ->login()
            ->passwordReset()
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->colors([
                'primary' => '#b70000',
                'secondary' => '#00008b',
            ])
            // ->brandLogo(asset('images/logo-sija.png'))
            ->brandName('REMI DKV')
            ->favicon(asset('icons/maskot.png'))
            ->brandLogoHeight('5rem')
            ->discoverResources(in: app_path('Filament/DKV/Resources'), for: 'App\\Filament\\DKV\\Resources')
            ->discoverPages(in: app_path('Filament/DKV/Pages'), for: 'App\\Filament\\DKV\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/DKV/Widgets'), for: 'App\\Filament\\DKV\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->setIcon('heroicon-o-user')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    ->setNavigationGroup('Profile'),  
                FilamentEnvEditorPlugin::make()
                    ->navigationGroup('System Tools')
                    ->navigationLabel('My Env')
                    ->navigationIcon('heroicon-o-cog-8-tooth')
                    ->navigationSort(1)
                    ->slug('env-editor'),    
            ])
            ->navigationItems([
                NavigationItem::make('Scanner')
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-qr-code')
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                'role.admin_teknisi',
            ]);
    }
}
