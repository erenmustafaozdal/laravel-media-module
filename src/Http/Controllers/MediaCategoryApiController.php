<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Controllers;

use Illuminate\Http\Request;

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
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\MoveSuccess;
use ErenMustafaOzdal\LaravelMediaModule\Events\MediaCategory\MoveFail;
// requests
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\MediaCategory\ApiStoreRequest;
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\MediaCategory\ApiUpdateRequest;
use ErenMustafaOzdal\LaravelMediaModule\Http\Requests\MediaCategory\ApiMoveRequest;
// services
use LMBCollection;


class MediaCategoryApiController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param integer|null $id
     * @return array
     */
    public function index(Request $request, $id = null)
    {
        return $this->getNodes(MediaCategory::class, $id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setDefineValues(['type']);
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeNode(MediaCategory::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  MediaCategory $media_category
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, MediaCategory $media_category)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        $this->updateModel($media_category);

        return [
            'id'        => $media_category->id,
            'name'      => $media_category->name_uc_first
        ];
    }

    /**
     * Move the specified node.
     *
     * @param  ApiMoveRequest $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function move(ApiMoveRequest $request, $id)
    {
        $media_category = MediaCategory::findOrFail($id);
        $this->setDefineValues(['type']);
        $this->setEvents([
            'success'   => MoveSuccess::class,
            'fail'      => MoveFail::class
        ]);
        return $this->moveModel($media_category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  MediaCategory  $media_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaCategory $media_category)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($media_category);
    }

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        if($request->has('id')) {
            $media_category = MediaCategory::find($request->input('id'));
            $models = $media_category->descendants()->where('name', 'like', "%{$request->input('query')}%");

        } else {
            $models = MediaCategory::where('name', 'like', "%{$request->input('query')}%");
        }

        $models = $models->get(['id','parent_id','lft','rgt','depth','name','type'])
            ->toHierarchy();
        return LMBCollection::relationRender($models, 'children', '/', ['name', 'type']);
    }
}
