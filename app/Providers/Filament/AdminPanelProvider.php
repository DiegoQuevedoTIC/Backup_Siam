<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\MaxWidth;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Widgets\AdvancedStatsOverviewWidget;
use App\Filament\Widgets\TableroLogo;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Joaopaulolndev\FilamentGeneralSettings\FilamentGeneralSettingsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->passwordReset()
            ->profile()
            ->maxContentWidth(MaxWidth::ScreenExtraLarge)
            ->font('Roboto')
            ->authGuard('web')
            ->collapsibleNavigationGroups()
            ->sidebarFullyCollapsibleOnDesktop()
            ->brandName('Siam ERP')
            ->brandLogo(asset('images/Icons.png'))
            ->darkModeBrandLogo(asset('images/Icons1.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/Icons.png'))
            ->navigationGroups([
                NavigationGroup::make('Parametros Generales')
                    ->label('Parametros Generales')
                    ->collapsed(),
                NavigationGroup::make('Administracion de Terceros')
                    ->label('Administracion de Terceros')
                    ->collapsed(),
                NavigationGroup::make('Contabilidad')
                    ->label('Contabilidad')
                    ->collapsed(),
                NavigationGroup::make('Gestión de Asociados')
                    ->label('Gestión de Asociados')
                    ->collapsed(),
                NavigationGroup::make('Tesoreria')
                    ->label('Tesoreria')
                    ->collapsed(),
                NavigationGroup::make('Solidaridad y Bienestar')
                    ->label('Solidaridad y Bienestar')
                    ->collapsed(),
                NavigationGroup::make('Comunicación Externa')
                    ->label('Comunicación Externa')
                    ->collapsed(),
                NavigationGroup::make('Gestion Documental')
                    ->label('Gestion Documental')
                    ->collapsed(),
                NavigationGroup::make('Informes Cumplimiento')
                    ->label('Informes Cumplimiento')
                    ->collapsed(),
                NavigationGroup::make('Roles y Permisos')
                    ->label('Roles y Permisos')
                    ->collapsed(),
            ])
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Emerald,
                'success' => Color::Red,
                'warning' => Color::Orange,
                'secondary' => Color::Indigo,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                TableroLogo::class,
                Widgets\AccountWidget::class,
                AdvancedStatsOverviewWidget::class,
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
            ])
            ->databaseNotifications()
            ->spa()
            ->plugins([
                FilamentSpatieRolesPermissionsPlugin::make(),
                SpotlightPlugin::make(),
                FilamentGeneralSettingsPlugin::make()
                    ->canAccess(fn() => auth()->user()->id === 10)
            ]);
    }
}
