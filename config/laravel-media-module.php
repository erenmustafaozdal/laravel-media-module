<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General config
    |--------------------------------------------------------------------------
    */
    'date_format'           => 'd.m.Y H:i:s',
    'icons' => [
        'media'             => 'icon-picture',
        'media_category'    => 'icon-notebook'
    ],

    /*
    |--------------------------------------------------------------------------
    | URL config
    |--------------------------------------------------------------------------
    */
    'url' => [
        'media_category'            => 'media-categories',      // media categories url
        'media'                     => 'medias',                // medias url
        'admin_url_prefix'          => 'admin',                 // admin dashboard url prefix
        'middleware'                => ['auth', 'permission']   // media module middleware
    ],

    /*
    |--------------------------------------------------------------------------
    | Controller config
    | if you make some changes on controller, you create your controller
    | and then extend the Laravel Media Module Controller. If you don't need
    | change controller, don't touch this config
    |--------------------------------------------------------------------------
    */
    'controller' => [
        'media_category_admin_namespace'    => 'ErenMustafaOzdal\LaravelMediaModule\Http\Controllers',
        'media_admin_namespace'             => 'ErenMustafaOzdal\LaravelMediaModule\Http\Controllers',
        'media_category_api_namespace'      => 'ErenMustafaOzdal\LaravelMediaModule\Http\Controllers',
        'media_api_namespace'               => 'ErenMustafaOzdal\LaravelMediaModule\Http\Controllers',
        'media_category'                    => 'MediaCategoryController',
        'media'                             => 'MediaController',
        'media_category_api'                => 'MediaCategoryApiController',
        'media_api'                         => 'MediaApiController'
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes on / off
    | if you don't use any route; set false
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'admin' => [
            'media_category'                => true,        // admin media category resource route
            'media'                         => true,        // admin media resource route
            'media_publish'                 => true,        // admin media publish get route
            'media_notPublish'              => true,        // admin media not publish get route
            'category_categories'           => true,        // admin category nested categories resource route
            'category_medias'               => true,        // admin category medias resource route
            'category_medias_publish'       => true,        // admin category medias publish get route
            'category_medias_notPublish'    => true         // admin category medias not publish get route
        ],
        'api' => [
            'media_category'                => true,        // api media category resource route
            'media_category_models'         => true,        // api media category model post route
            'media_category_move'           => true,        // api media category move post route
            'media_category_detail'         => true,        // api media category detail get route
            'media'                         => true,        // api media resource route
            'media_group'                   => true,        // api media group post route
            'media_detail'                  => true,        // api media detail get route
            'media_fastEdit'                => true,        // api media fast edit post route
            'media_publish'                 => true,        // api media publish post route
            'media_notPublish'              => true,        // api media not publish post route
            'media_removePhoto'             => true,        // api media destroy photo post route
            'category_categories_index'     => true,        // api category nested categories index get route
            'category_medias_index'         => true,        // api category medias index get route
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | View config
    |--------------------------------------------------------------------------
    | dot notation of blade view path, its position on the /resources/views directory
    */
    'views' => [
        // media category view
        'media_category' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // media layout
            'index'     => 'laravel-modules-core::media_category.index',        // get media category index view blade
            'create'    => 'laravel-modules-core::media_category.operation',    // get media category create view blade
            'show'      => 'laravel-modules-core::media_category.show',         // get media category show view blade
            'edit'      => 'laravel-modules-core::media_category.operation',    // get media category edit view blade
        ],
        // media view
        'media' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // media layout
            'index'     => 'laravel-modules-core::media.index',                 // get media index view blade
            'create'    => 'laravel-modules-core::media.operation',             // get media create view blade
            'show'      => 'laravel-modules-core::media.show',                  // get media show view blade
            'edit'      => 'laravel-modules-core::media.operation',             // get media edit view blade
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | ## Options
    |
    | - default_img_path                : model default avatar or photo
    |
    | --- uploads                       : model uploads options
    | - relation                        : file is in the relation table and what is relation type [false|hasOne|hasMany]
    | - relation_model                  : relation model [\App\Model etc...]
    | - type                            : file type [image,file]
    | - column                          : file database column
    | - path                            : file path
    | - max_size                        : file allowed maximum size
    | - max_file                        : maximum file count
    | - aspect_ratio                    : if file is image; crop aspect ratio
    | - mimes                           : file allowed mimes
    | - thumbnails                      : if file is image; its thumbnails options
    |
    | NOT: Thumbnails fotoğrafları yüklenirken bakılır:
    |       1. eğer post olarak x1, y1, x2, y2, width ve height değerleri gönderilmemiş ise bu değerlere göre
    |       thumbnails ayarlarında belirtilen resimleri sistem içine kaydeder.
    |       Yani bu değerler post edilmişse aşağıdaki değerleri yok sayar.
    |       2. Eğer yukarıdaki ilgili değerler post edilmemişse, thumbnails ayarlarında belirtilen değerleri
    |       dikkate alarak thumbnails oluşturur
    |
    |       Ölçü Belirtme:
    |       1. İstenen resmin width ve height değerleri verilerek istenen net bir ölçüde resimler oluşturulabilir
    |       2. Width değeri null verilerek, height değerine göre ölçeklenebilir
    |       3. Height değeri null verilerek, width değerine göre ölçeklenebilir
    |--------------------------------------------------------------------------
    */
    'media' => [
        'default_img_path'              => 'vendor/laravel-modules-core/assets/global/img/media',
        'uploads' => [
            // media photo options
            'photo' => [
                'relation'              => 'hasOne',
                'relation_model'        => '\App\MediaPhoto',
                'type'                  => 'image',
                'column'                => 'photo.photo',
                'path'                  => 'uploads/media',
                'max_size'              => '5120',
                'aspect_ratio'          => 16/9,
                'mimes'                 => 'jpeg,jpg,jpe,png',
                'thumbnails' => [
                    'small'             => [ 'width' => 35, 'height' => null],
                    'normal'            => [ 'width' => 300, 'height' => null],
                    'big'               => [ 'width' => 800, 'height' => null],
                ]
            ]
        ]
    ],
];
