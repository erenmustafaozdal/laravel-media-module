<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Media;
use App\MediaCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
// events
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\StoreSuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\StoreFail;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\UpdateSuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\UpdateFail;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\DestroySuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\DestroyFail;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\PublishSuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\PublishFail;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\NotPublishSuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\Media\NotPublishFail;
// requests
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media\ApiStoreRequest;
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media\ApiUpdateRequest;


class MediaApiController extends BaseController
{
    /**
     * default relation datas
     *
     * @var array
     */
    private $relations = [
        'video' => [
            'relation_type'     => 'hasOne',
            'relation'          => 'video',
            'relation_model'    => '\App\MediaVideo',
            'datas' => [
                'video'   => null
            ]
        ]
    ];

    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'publish'       => ['route' => 'api.media.publish', 'id' => true],
        'not_publish'   => ['route' => 'api.media.notPublish', 'id' => true],
        'edit_page'     => ['route' => 'admin.media.edit', 'id' => true]
    ];

    /**
     * default realtion urls of the model
     *
     * @var array
     */
    private $relationUrls = [
        'edit_page' => [
            'route'     => 'admin.media_category.media.edit',
            'id'        => 0,
            'model'     => ''
        ],
        'show' => [
            'route'     => 'admin.media_category.media.show',
            'id'        => 0,
            'model'     => ''
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @param integer|null $id
     * @return Datatables
     */
    public function index(Request $request, $id = null)
    {
        // query
        if (is_null($id)) {
            $medias = Media::with('categories','video','photo');
        } else {
            $category = MediaCategory::findOrFail($id);
            $medias = $category->medias()->with([
                'categories' => function($q) use($id)
                {
                    $q->where('id', '!=', $id);
                },
                $category->type
            ]);
        }
        $medias->select(['medias.id','medias.title','medias.description','medias.is_publish','medias.created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $medias->filter($request);
        }

        // urls
        $addUrls = $this->urls;
        if( ! is_null($id)) {
            $this->relationUrls['edit_page']['id'] = $id;
            $this->relationUrls['edit_page']['model'] = config('laravel-media-module.url.media');
            $this->relationUrls['show']['id'] = $id;
            $this->relationUrls['show']['model'] = config('laravel-media-module.url.media');
            $addUrls = array_merge($addUrls, $this->relationUrls);
        }
        $addColumns = [
            'addUrls'           => $addUrls,
            'status'            => function($model) { return $model->is_publish; },
            'media'             => function($model)
            {
                if ( ! is_null($model->video) ) {
                    return $model->video->html;
                }
                if ( ! is_null($model->photo) ) {
                    return $model->photo->html;
                }
                return '';
            }
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; },
            'title'             => function($model) { return $model->title_uc_first; },
            'categories'        => function($model) {
                return $model->categories->map(function($item,$key)
                {
                    $item->name = $item->name_uc_first;
                    return $item;
                })->all();
            },
        ];
        $removeColumns = ['is_publish','photo','video'];
        return $this->getDatatables($medias, $addColumns, $editColumns, $removeColumns);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function detail($id, Request $request)
    {
        $media = Media::with([
            'categories' => function($query) use($request)
            {
                $refferer = explode('/', removeDomain($request->server('HTTP_REFERER')));
                $id = $refferer[1] === config('laravel-media-module.url.media_category') ? $refferer[2] : 0;
                return $query->select(['id','name'])->where('id', '!=', $id);
            },
            'video' => function($query)
            {
                return $query->select(['id','media_id','video']);
            },
            'photo' => function($query)
            {
                return $query->select(['id','media_id','photo']);
            }
        ])->where('id',$id)->select(['id','title','description','created_at','updated_at']);

        $editColumns = [
            'size'          => function($model) { return $model->size_table; },
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; },
            'photo.photo'   => function($model) { return !is_null($model->photo) ? $model->photo->url : ''; },
            'video.video'   => function($model) { return !is_null($model->video) ? $model->video->embed_url : ''; },
            'title'             => function($model) { return $model->title_uc_first; },
            'categories'        => function($model) {
                return $model->categories->map(function($item,$key)
                {
                    $item->name = $item->name_uc_first;
                    return $item;
                })->all();
            },
        ];
        return $this->getDatatables($media, [], $editColumns, []);
    }

    /**
     * get model data for edit
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function fastEdit($id, Request $request)
    {
        return Media::with([
            'categories' => function($query) use($request)
            {
                $refferer = explode('/', removeDomain($request->server('HTTP_REFERER')));
                $id = $refferer[1] === config('laravel-media-module.url.media_category') ? $refferer[2] : 0;
                return $query->select(['id','name'])->where('id', '!=', $id);
            }
        ])->where('id',$id)->first(['id','title','description','is_publish']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setToFileOptions($request, ['photo.photo' => 'photo']);
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        if ($request->has('video')) {
            $this->relations['video']['datas']['video'] = $request->video;
            $this->setOperationRelation($this->relations);
        }
        return $this->storeModel(Media::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Media $media
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, Media $media)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($media);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($media);
    }

    /**
     * publish model
     *
     * @param Media $media
     * @return \Illuminate\Http\Response
     */
    public function publish(Media $media)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($media, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]);
    }

    /**
     * not publish model
     *
     * @param Media $media
     * @return \Illuminate\Http\Response
     */
    public function notPublish(Media $media)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($media, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
    }

    /**
     * group action method
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(Request $request)
    {
        $this->clearCache();
        if ( $this->groupAlias(Media::class) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }

    /**
     * clear cache
     *
     * @return void
     */
    private function clearCache()
    {
        \Cache::forget(implode('_',['medias','enterprise_photo'])); // kurumsal foto
        \Cache::forget(implode('_',['medias','media_we_photo'])); // basında biz foto
        \Cache::forget('home_videos'); // ana sayfa videolar

        $totalPages = (int) ceil(\DB::table('medias')->count()/6) + 1;
        foreach(\DB::table('media_categories')->get(['id']) as $category) {
            \Cache::forget(implode('_', ['media_categories', 'descendantsAndSelf', 'withMedias', $category->id]));
            for($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['category_medias',$category->id,'page',$i]));
            }
        }
    }
}
