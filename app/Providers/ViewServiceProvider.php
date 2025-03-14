<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use TomatoPHP\FilamentSettingsHub\Models\Setting;

class ViewServiceProvider extends ServiceProvider
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
        // Compartir el nombre del sitio con todas las vistas
        // View::composer('*', function ($view) {

        //     $siteName = setting('site_name', 'Concursos y Sorteos San Miguel');
        //     $view->with('siteName', $siteName);
        // });
    }
}
