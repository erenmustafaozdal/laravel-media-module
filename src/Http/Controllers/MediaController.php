<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Controllers;

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
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media\StoreRequest;
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media\UpdateRequest;

class MediaController extends BaseController
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
        ],
        'photo' => [
            'relation_type'     => 'hasOne',
            'relation'          => 'photo',
            'relation_model'    => '\App\MediaPhoto',
            'datas' => [
                'photo'   => null
            ]
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (is_null($id)) {
            return view(config('laravel-media-module.views.media.index'));
        }

        $media_category = MediaCategory::findOrFail($id);
        return view(config('laravel-media-module.views.media.index'), compact('media_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $operation = 'create';
        if (is_null($id)) {
            return view(config('laravel-media-module.views.media.create'), compact('operation'));
        }

        $media_category = MediaCategory::findOrFail($id);
        return view(config('laravel-media-module.views.media.create'), compact('media_category','operation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $id = null)
    {
        if (is_null($id)) {
            $redirect = 'index';
        } else {
            $redirect = 'media_category.media.index';
            $this->setRelationRouteParam($id, config('laravel-media-module.url.media'));
        }

        $this->setToFileOptions($request, ['photo.photo' => 'photo']);
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        if ($request->has('video')) {
            $this->relations['video']['datas']['video'] = $request->video;
            $this->setOperationRelation($this->relations);
        }
        return $this->storeModel(Media::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|Media $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $media = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-media-module.views.media.show'), compact('media'));
        }

        $media_category = MediaCategory::findOrFail($firstId);
        return view(config('laravel-media-module.views.media.show'), compact('media', 'media_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|Media $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $media = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-media-module.views.media.edit'), compact('media','operation'));
        }

        $media_category = MediaCategory::findOrFail($firstId);
        return view(config('laravel-media-module.views.media.edit'), compact('media', 'media_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|Media $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $media = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'media_category.media.show';
            $this->setRelationRouteParam($firstId, config('laravel-media-module.url.media'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($media,$redirect, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|Media $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $media = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'media_category.media.index';
            $this->setRelationRouteParam($firstId, config('laravel-media-module.url.media'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($media,$redirect);
    }

    /**
     * publish model
     *
     * @param integer|Media $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function publish($firstId, $secondId = null)
    {
        $media = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'media_category.media.show';
            $this->setRelationRouteParam($firstId, config('laravel-media-module.url.media'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($media, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ],$redirect);
    }

    /**
     * not publish model
     *
     * @param integer|Media $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function notPublish($firstId, $secondId = null)
    {
        $media = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'media_category.media.show';
            $this->setRelationRouteParam($firstId, config('laravel-media-module.url.media'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($media, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ],$redirect);
    }
}
