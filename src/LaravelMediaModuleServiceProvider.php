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
        // merge default configs with publish configs
        $this->mergeDefaultConfig();

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-media-module.url.media'),  'App\Media');
        $router->model(config('laravel-media-module.url.media_category'),  'App\MediaCategory');
    }

    /**
     * merge default configs with publish configs
     */
    protected function mergeDefaultConfig()
    {
        $config = $this->app['config']->get('laravel-media-module', []);
        $default = require __DIR__.'/../config/default.php';

        $config['media_types'] = $default['media_types'];

        // admin media category routes
        $route = $config['routes']['admin']['media_category'];
        $default['routes']['admin']['media_category'] = $route;
        // admin media routes
        $route = $config['routes']['admin']['media'];
        $default['routes']['admin']['media'] = $route;
        $default['routes']['admin']['media_publish'] = $route;
        $default['routes']['admin']['media_notPublish'] = $route;
        // admin sub media categories nested categories
        $route = $config['routes']['admin']['nested_sub_categories'];
        $default['routes']['admin']['category_categories'] = $route;
        // admin sub media categories medias
        $route = $config['routes']['admin']['sub_category_medias'];
        $default['routes']['admin']['category_medias'] = $route;
        $default['routes']['admin']['category_medias_publish'] = $route;
        $default['routes']['admin']['category_medias_notPublish'] = $route;

        // api media category routes
        $apiCat = $config['routes']['api']['media_category'];
        $default['routes']['api']['media_category'] = $apiCat;
        // api sub media categories nested categories
        $apiSubCat = $config['routes']['api']['nested_sub_categories'];
        $default['routes']['api']['category_categories_index'] = $apiSubCat;

        $default['routes']['api']['media_category_models'] = $apiCat || $apiSubCat;
        $default['routes']['api']['media_category_move'] = $apiCat || $apiSubCat;
        $default['routes']['api']['media_category_detail'] = $apiCat || $apiSubCat;

        // api media routes
        $model = $config['routes']['api']['media'];
        $default['routes']['api']['media'] = $model;
        // api sub media categories medias
        $subModel = $config['routes']['api']['sub_category_medias'];
        $default['routes']['api']['category_medias_index'] = $subModel;

        $default['routes']['api']['media_group'] = $model || $subModel;
        $default['routes']['api']['media_detail'] = $model || $subModel;
        $default['routes']['api']['media_fastEdit'] = $model || $subModel;
        $default['routes']['api']['media_publish'] = $model || $subModel;
        $default['routes']['api']['media_notPublish'] = $model || $subModel;

        $config['routes'] = $default['routes'];


        $default['media']['uploads']['photo']['path'] = unsetReturn($config['media']['uploads'],'path');
        $default['media']['uploads']['photo']['max_size'] = unsetReturn($config['media']['uploads'],'max_size');
        $default['media']['uploads']['photo']['aspect_ratio'] = unsetReturn($config['media']['uploads'],'photo_aspect_ratio');
        $default['media']['uploads']['photo']['mimes'] = unsetReturn($config['media']['uploads'],'photo_mimes');
        $default['media']['uploads']['photo']['thumbnails'] = unsetReturn($config['media']['uploads'],'photo_thumbnails');
        $config['media']['uploads']['photo'] = $default['media']['uploads']['photo'];


        // model photo uploads
        $config['media']['uploads']['photo']['relation'] = $default['media']['uploads']['photo']['relation'];
        $config['media']['uploads']['photo']['relation_model'] = $default['media']['uploads']['photo']['relation_model'];
        $config['media']['uploads']['photo']['type'] = $default['media']['uploads']['photo']['type'];
        $config['media']['uploads']['photo']['column'] = $default['media']['uploads']['photo']['column'];

        $this->app['config']->set('laravel-media-module', $config);
    }
}
