<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General config
    |--------------------------------------------------------------------------
    */
    'media_types'       => [ 'photo', 'video', 'mixed' ],
    'gallery_types'     => [ 'classical', 'modern', 'categorization' ],

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
            'media_category_detail'         => true,        // api media category detail post route
            'media'                         => true,        // api media resource route
            'media_group'                   => true,        // api media group post route
            'media_detail'                  => true,        // api media detail get route
            'media_fastEdit'                => true,        // api media fast edit post route
            'media_publish'                 => true,        // api media publish post route
            'media_notPublish'              => true,        // api media not publish post route
            'category_categories_index'     => true,        // api category nested categories index get route
            'category_medias_index'         => true,        // api category medias index get route
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
            ]
        ]
    ],
];
