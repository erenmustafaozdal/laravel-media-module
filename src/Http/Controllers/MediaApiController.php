<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Media;
use App\MediaCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;
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
            $medias = $category->medias()->with('categories',$category->type);
        }
        $medias->select(['id','title','description','is_publish','created_at']);

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
            'created_at'        => function($model) { return $model->created_at_table; }
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
            'categories' => function($query)
            {
                return $query->select(['id','name']);
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
            'photo.photo'   => function($model) { return !is_null($model->photo) ? $model->photo->getPhoto(['class' => 'img-responsive'], 'normal', true, 'media','media') : ''; },
            'video.video'   => function($model) { return !is_null($model->video) ? $model->video->embed_url : ''; },
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
            'categories' => function($query)
            {
                return $query->select(['id','name']);
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
        if ( $this->groupAlias(Media::class) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }
}
