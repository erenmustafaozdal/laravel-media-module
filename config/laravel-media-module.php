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
            'media_category'            => true,        // Is the route to be used categories admin
            'media'                     => true,        // Is the route to be used medias admin
            'nested_sub_categories'     => true,        // Did subcategory nested categories admin route will be used
            'sub_category_medias'       => true,        // Did subcategory media admin route will be used
        ],
        'api' => [
            'media_category'            => true,        // Is the route to be used categories api
            'media'                     => true,        // Is the route to be used medias api
            'nested_sub_categories'     => true,        // Did subcategory nested categories api route will be used
            'sub_category_medias'       => true,        // Did subcategory media api route will be used
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
            'path'                  => 'uploads/media',
            'max_size'              => '5120',
            'photo_aspect_ratio'    => 16/9,
            'photo_mimes'           => 'jpeg,jpg,jpe,png',
            'photo_thumbnails' => [
                'small'             => [ 'width' => 35, 'height' => null],
                'normal'            => [ 'width' => 300, 'height' => null],
                'big'               => [ 'width' => 800, 'height' => null],
            ]
        ]
    ],






    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'media_category' => [
            'title'                 => 'Medya Kategorileri',
            'routes' => [
                'admin.media_category.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar medya kategorileri veri tablosu sayfasına gidebilir.',
                ],
                'admin.media_category.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar medya kategorisi ekleme sayfasına gidebilir',
                ],
                'admin.media_category.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar medya kategorisi ekleyebilir',
                ],
                'admin.media_category.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar medya kategorisi bilgilerini görüntüleyebilir',
                ],
                'admin.media_category.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar medya kategorisini düzenleme sayfasına gidebilir',
                ],
                'admin.media_category.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar medya kategorisini düzenleyebilir',
                ],
                'admin.media_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar medya kategorisini silebilir',
                ],
                'api.media_category.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar medya kategorilerini veri tablosunda listeleyebilir',
                ],
                'api.media_category.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar medya kategorilerini veri tablosunda hızlı ekleyebilir.',
                ],
                'api.media_category.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar medya kategorilerini veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.media_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar medya kategorilerini veri tablosunda silebilir',
                ],
                'api.media_category.models' => [
                    'title'         => 'Seçim İçin Listeleme',
                    'description'   => 'Bu izne sahip olanlar medya kategorilerini bazı seçim kutularında listeleyebilir',
                ],
                'api.media_category.move' => [
                    'title'         => 'Taşıma',
                    'description'   => 'Bu izne sahip olanlar medya kategorilerini taşıyarak yerini değiştirebilir.',
                ],
                'api.media_category.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar medya kategorilerinin detay bilgilerini getirebilir.',
                ],
            ],
        ],
        'media' => [
            'title'                 => 'Medyalar',
            'routes' => [
                'admin.media.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar medyalar veri tablosu sayfasına gidebilir.',
                ],
                'admin.media.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar medya ekleme sayfasına gidebilir',
                ],
                'admin.media.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar medya ekleyebilir',
                ],
                'admin.media.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar medya bilgilerini görüntüleyebilir',
                ],
                'admin.media.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar medyayı düzenleme sayfasına gidebilir',
                ],
                'admin.media.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar medyayı düzenleyebilir',
                ],
                'admin.media.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar medyayı silebilir',
                ],
                'admin.media.publish' => [
                    'title'         => 'Yayınlama',
                    'description'   => 'Bu izne sahip olanlar medyayı yayınlayabilir',
                ],
                'admin.media.notPublish' => [
                    'title'         => 'Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar medyayı yayından kaldırabilir',
                ],
                'api.media.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar medyaları veri tablosunda listeleyebilir',
                ],
                'api.media.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar medyaları veri tablosunda hızlı ekleyebilir.',
                ],
                'api.media.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar medyaları veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.media.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar medyaları veri tablosunda silebilir',
                ],
                'api.media.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar medyalar veri tablosunda toplu işlem yapabilir',
                ],
                'api.media.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar medyalar tablosunda detayını görebilir.',
                ],
                'api.media.fastEdit' => [
                    'title'         => 'Hızlı Düzenleme Bilgileri',
                    'description'   => 'Bu izne sahip olanlar medyalar tablosunda hızlı düzenleme amacıyla bilgileri getirebilir.',
                ],
                'api.media.publish' => [
                    'title'         => 'Hızlı Yayınlama',
                    'description'   => 'Bu izne sahip olanlar medyalar tablosunda medyayı yayınlanyabilir.',
                ],
                'api.media.notPublish' => [
                    'title'         => 'Hızlı Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar medyalar tablosunda medyayı yayından kaldırabilir.',
                ],
            ],
        ],
    ],
];
