<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Controllers;

use App\Http\Requests;
use App\MediaCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\StoreSuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\StoreFail;
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\UpdateSuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\UpdateFail;
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\DestroySuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\MediaCategory\StoreRequest;
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\MediaCategory\UpdateRequest;

class MediaCategoryController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (is_null($id)) {
            return view(config('laravel-media-module.views.media_category.index'));
        }

        $parent_media_category = MediaCategory::findOrFail($id);
        return view(config('laravel-media-module.views.media_category.index'), compact('parent_media_category'));
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
            return view(config('laravel-media-module.views.media_category.create'), compact('operation'));
        }

        $parent_media_category = MediaCategory::findOrFail($id);
        return view(config('laravel-media-module.views.media_category.create'), compact('parent_media_category','operation'));
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
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        if (is_null($id)) {
            $redirect = 'index';
            return $this->storeModel(MediaCategory::class,$redirect);
        }
        $redirect = 'media_category.media_category.index';
        $this->setRelationRouteParam($id, config('laravel-media-module.url.media_category'));
        $this->setDefineValues(['type']);
        return $this->storeNode(MediaCategory::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|MediaCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $media_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-media-module.views.media_category.show'), compact('media_category'));
        }

        $parent_media_category = MediaCategory::findOrFail($firstId);
        return view(config('laravel-media-module.views.media_category.show'), compact('parent_media_category','media_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|MediaCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $media_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-media-module.views.media_category.edit'), compact('media_category','operation'));
        }

        $parent_media_category = MediaCategory::findOrFail($firstId);
        return view(config('laravel-media-module.views.media_category.edit'), compact('parent_media_category','media_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|MediaCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $media_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'media_category.media_category.show';
            $this->setRelationRouteParam($firstId, config('laravel-media-module.url.media_category'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($media_category, $redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|MediaCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $media_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'media_category.media_category.index';
            $this->setRelationRouteParam($firstId, config('laravel-media-module.url.media_category'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($media_category, $redirect);
    }
}
