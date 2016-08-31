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
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * all medias of the category descendants and self
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type [photo,video]
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllMedias($query, $type)
    {
        return $query->with([
            'medias' => function ($q) use($type)
            {
                $q->select('id','title','description','is_publish')->with($type);
            }
        ]);
    }





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
        return $this->belongsToMany('App\Media')->withTimestamps();
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
