<?php

namespace ErenMustafaOzdal\LaravelMediaModule;

use Baum\Node;
use Illuminate\Http\Request;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class MediaCategory extends Node
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','type'];



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    /**
     * set nodes
     *
     * @param $request
     * @param string $type => move|store
     */
    public function setNode(Request $request, $type = 'store')
    {
        if ( ! $request->has('position')) {
            $model = MediaCategory::find($request->input('parent'));
            $this->makeChildOf($model);
            return;
        }

        $input = $type === 'store' ? 'parent' : 'related';
        switch($request->input('position')) {
            case 'firstChild':
                $model = MediaCategory::find($request->input($input));
                $this->makeFirstChildOf($model);
                break;
            case 'lastChild':
                $model = MediaCategory::find($request->input($input));
                $this->makeChildOf($model);
                break;
            case 'before':
                $model = MediaCategory::find($request->input('related'));
                $this->moveToLeftOf($model);
                break;
            case 'after':
                $model = MediaCategory::find($request->input('related'));
                $this->moveToRightOf($model);
                break;
        }
    }





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the medias of the media category.
     */
    public function medias()
    {
        return $this->hasMany('App\Media','category_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
