<?php

namespace ErenMustafaOzdal\LaravelMediaModule;

use Illuminate\Support\ServiceProvider;

class LaravelMediaModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/laravel-media-module.php' => config_path('laravel-media-module.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('ErenMustafaOzdal\LaravelModulesBase\LaravelModulesBaseServiceProvider');
        $this->app->register('Baum\Providers\BaumServiceProvider');

        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-media-module.php', 'laravel-media-module'
        );

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-media-module.url.media'),  'App\Media');
        $router->model(config('laravel-media-module.url.media_category'),  'App\MediaCategory');
    }
}
