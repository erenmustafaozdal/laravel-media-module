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
            $type . 's' => function($q)
            {
                $q->with([
                    'media' => function ($q)
                    {
                        $q->select('id','title','description','is_publish');
                    }
                ]);
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
        return $this->hasMany('App\Media','category_id');
    }

    /**
     * Get all of the videos for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function videos()
    {
        return $this->hasManyThrough('App\MediaVideo', 'App\Media', 'category_id', 'media_id');
    }

    /**
     * Get all of the photos for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function photos()
    {
        return $this->hasManyThrough('App\MediaPhoto', 'App\Media', 'category_id', 'media_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
