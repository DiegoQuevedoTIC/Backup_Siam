<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;

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
        Model::unguard();
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        FilamentAsset::register([
            Js::make('jquery.min', __DIR__ . '/../../resources/js/jquery.min.js'),
            //Js::make('plan_desembolso', __DIR__ . '/../../resources/js/plan_desembolso.js'),
        ]);
    }
}
