<?php
//max level nested function 100 hatasını düzeltiyor
ini_set('xdebug.max_nesting_level', 300);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
/*==========  Document Category Module  ==========*/
Route::group([
    'prefix' => config('laravel-media-module.url.admin_url_prefix'),
    'middleware' => config('laravel-media-module.url.middleware'),
    'namespace' => config('laravel-media-module.controller.media_category_admin_namespace')
], function()
{
    if (config('laravel-media-module.routes.admin.media_category')) {
        Route::resource(config('laravel-media-module.url.media_category'), config('laravel-media-module.controller.media_category'), [
            'names' => [
                'index'         => 'admin.media_category.index',
                'create'        => 'admin.media_category.create',
                'store'         => 'admin.media_category.store',
                'show'          => 'admin.media_category.show',
                'edit'          => 'admin.media_category.edit',
                'update'        => 'admin.media_category.update',
                'destroy'       => 'admin.media_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-media-module.routes.admin.category_categories')) {
        Route::group(['middleware' => 'nested_model:DocumentCategory'], function() {
            Route::resource(config('laravel-media-module.url.media_category') . '/{id}/' . config('laravel-media-module.url.media_category'), config('laravel-media-module.controller.media_category'), [
                'names' => [
                    'index' => 'admin.media_category.media_category.index',
                    'create' => 'admin.media_category.media_category.create',
                    'store' => 'admin.media_category.media_category.store',
                    'show' => 'admin.media_category.media_category.show',
                    'edit' => 'admin.media_category.media_category.edit',
                    'update' => 'admin.media_category.media_category.update',
                    'destroy' => 'admin.media_category.media_category.destroy',
                ]
            ]);
        });
    }
});

/*==========  Document Module  ==========*/
Route::group([
    'prefix'        => config('laravel-media-module.url.admin_url_prefix'),
    'middleware'    => config('laravel-media-module.url.middleware'),
    'namespace'     => config('laravel-media-module.controller.media_admin_namespace')
], function()
{
    // admin publish media
    if (config('laravel-media-module.routes.admin.media_publish')) {
        Route::get('media/{' . config('laravel-media-module.url.media') . '}/publish', [
            'as'                => 'admin.media.publish',
            'uses'              => config('laravel-media-module.controller.media').'@publish'
        ]);
    }
    // admin not publish media
    if (config('laravel-media-module.routes.admin.media_notPublish')) {
        Route::get('media/{' . config('laravel-media-module.url.media') . '}/not-publish', [
            'as'                => 'admin.media.notPublish',
            'uses'              => config('laravel-media-module.controller.media').'@notPublish'
        ]);
    }
    if (config('laravel-media-module.routes.admin.media')) {
        Route::resource(config('laravel-media-module.url.media'), config('laravel-media-module.controller.media'), [
            'names' => [
                'index'         => 'admin.media.index',
                'create'        => 'admin.media.create',
                'store'         => 'admin.media.store',
                'show'          => 'admin.media.show',
                'edit'          => 'admin.media.edit',
                'update'        => 'admin.media.update',
                'destroy'       => 'admin.media.destroy',
            ]
        ]);
    }

    /*==========  Category medias  ==========*/
    // admin publish media
    if (config('laravel-media-module.routes.admin.category_medias_publish')) {
        Route::get(config('laravel-media-module.url.media_category') . '/{id}/' . config('laravel-media-module.url.media') . '/{' . config('laravel-media-module.url.media') . '}/publish', [
            'middleware'        => 'related_model:DocumentCategory,medias',
            'as'                => 'admin.media_category.media.publish',
            'uses'              => config('laravel-media-module.controller.media').'@publish'
        ]);
    }
    // admin not publish media
    if (config('laravel-media-module.routes.admin.category_medias_notPublish')) {
        Route::get(config('laravel-media-module.url.media_category') . '/{id}/' . config('laravel-media-module.url.media') . '/{' . config('laravel-media-module.url.media') . '}/not-publish', [
            'middleware'        => 'related_model:DocumentCategory,medias',
            'as'                => 'admin.media_category.media.notPublish',
            'uses'              => config('laravel-media-module.controller.media').'@notPublish'
        ]);
    }

    // category medias
    if (config('laravel-media-module.routes.admin.category_medias')) {
        Route::group(['middleware' => 'related_model:DocumentCategory,medias'], function() {
            Route::resource(config('laravel-media-module.url.media_category') . '/{id}/' . config('laravel-media-module.url.media'), config('laravel-media-module.controller.media'), [
                'names' => [
                    'index' => 'admin.media_category.media.index',
                    'create' => 'admin.media_category.media.create',
                    'store' => 'admin.media_category.media.store',
                    'show' => 'admin.media_category.media.show',
                    'edit' => 'admin.media_category.media.edit',
                    'update' => 'admin.media_category.media.update',
                    'destroy' => 'admin.media_category.media.destroy',
                ]
            ]);
        });
    }
});



/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/
/*==========  Document Category Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-media-module.url.middleware'),
    'namespace'     => config('laravel-media-module.controller.media_category_api_namespace')
], function()
{
    // api media category
    if (config('laravel-media-module.routes.api.media_category_models')) {
        Route::post('media-category/models', [
            'as'                => 'api.media_category.models',
            'uses'              => config('laravel-media-module.controller.media_category_api').'@models'
        ]);
    }
    // api media category move
    if (config('laravel-media-module.routes.api.media_category_move')) {
        Route::post('media-category/{id}/move', [
            'as'                => 'api.media_category.move',
            'uses'              => config('laravel-media-module.controller.media_category_api').'@move'
        ]);
    }
    // data table detail row
    if (config('laravel-media-module.routes.api.media_category_detail')) {
        Route::get('media-category/{id}/detail', [
            'as'                => 'api.media_category.detail',
            'uses'              => config('laravel-media-module.controller.media_category_api').'@detail'
        ]);
    }
    // media category resource
    if (config('laravel-media-module.routes.api.media_category')) {
        Route::resource(config('laravel-media-module.url.media_category'), config('laravel-media-module.controller.media_category_api'), [
            'names' => [
                'index'         => 'api.media_category.index',
                'store'         => 'api.media_category.store',
                'update'        => 'api.media_category.update',
                'destroy'       => 'api.media_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-media-module.routes.api.category_categories_index')) {
        Route::get(config('laravel-media-module.url.media_category') . '/{id}/' . config('laravel-media-module.url.media_category'), [
            'middleware'        => 'nested_model:DocumentCategory',
            'as'                => 'api.media_category.media_category.index',
            'uses'              => config('laravel-media-module.controller.media_category_api').'@index'
        ]);
    }
});

/*==========  Document Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-media-module.url.middleware'),
    'namespace'     => config('laravel-media-module.controller.media_api_namespace')
], function()
{
    // api group action
    if (config('laravel-media-module.routes.api.media_group')) {
        Route::post('media/group-action', [
            'as'                => 'api.media.group',
            'uses'              => config('laravel-media-module.controller.media_api').'@group'
        ]);
    }
    // data table detail row
    if (config('laravel-media-module.routes.api.media_detail')) {
        Route::get('media/{id}/detail', [
            'as'                => 'api.media.detail',
            'uses'              => config('laravel-media-module.controller.media_api').'@detail'
        ]);
    }
    // get media category edit data for modal edit
    if (config('laravel-media-module.routes.api.media_fastEdit')) {
        Route::post('media/{id}/fast-edit', [
            'as'                => 'api.media.fastEdit',
            'uses'              => config('laravel-media-module.controller.media_api').'@fastEdit'
        ]);
    }
    // api publish media
    if (config('laravel-media-module.routes.api.media_publish')) {
        Route::post('media/{' . config('laravel-media-module.url.media') . '}/publish', [
            'as'                => 'api.media.publish',
            'uses'              => config('laravel-media-module.controller.media_api').'@publish'
        ]);
    }
    // api not publish media
    if (config('laravel-media-module.routes.api.media_notPublish')) {
        Route::post('media/{' . config('laravel-media-module.url.media') . '}/not-publish', [
            'as'                => 'api.media.notPublish',
            'uses'              => config('laravel-media-module.controller.media_api').'@notPublish'
        ]);
    }
    if (config('laravel-media-module.routes.api.media')) {
        Route::resource(config('laravel-media-module.url.media'), config('laravel-media-module.controller.media_api'), [
            'names' => [
                'index'         => 'api.media.index',
                'store'         => 'api.media.store',
                'update'        => 'api.media.update',
                'destroy'       => 'api.media.destroy',
            ]
        ]);
    }
    // category medias
    if (config('laravel-media-module.routes.api.category_medias_index')) {
        Route::get(config('laravel-media-module.url.media_category') . '/{id}/' . config('laravel-media-module.url.media'), [
            'middleware'        => 'related_model:DocumentCategory,medias',
            'as'                => 'api.media_category.media.index',
            'uses'              => config('laravel-media-module.controller.media_api').'@index'
        ]);
    }
});
