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
use Awcodes\Overlook\OverlookPlugin;
use Awcodes\Overlook\Widgets\OverlookWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('sija')
            ->login()
            ->passwordReset()
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->colors([
                'primary' => '#00bcd5',
            ])
            ->brandLogo(asset('images/logo-sija.png'))
            ->brandName('REMI SIJA')
            ->favicon(asset('icons/maskot.png'))
            ->brandLogoHeight('5rem')
            ->navigationItems([
                NavigationItem::make('Scanner')
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-qr-code')
            ])            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                OverlookWidget::class,
            ])
            ->plugins([
                FilamentEnvEditorPlugin::make()
                    ->navigationGroup('System Tools')
                    ->navigationLabel('My Env')
                    ->navigationIcon('heroicon-o-cog-8-tooth')
                    ->navigationSort(1)
                    ->slug('env-editor'),
                FilamentEditProfilePlugin::make()
                    ->setIcon('heroicon-o-user')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    ->setNavigationGroup('Profile'),
                OverlookPlugin::make()
                    ->includes([
                        \App\Filament\Resources\LaporanResource::class,
                        \App\Filament\Resources\InventarisResource::class,
                    ])
                    ->icons([
                        'heroicon-o-document-text' => \App\Filament\Resources\LaporanResource::class,
                        'heroicon-o-archive-box' => \App\Filament\Resources\InventarisResource::class,
                    ])
                    ->sort(2)
                    ->columns([
                        'lg' => 4,
                    ]),    
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
