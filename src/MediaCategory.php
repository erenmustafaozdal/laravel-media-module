<?php

namespace ErenMustafaOzdal\LaravelMediaModule;

use Baum\Node;
use Request;
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
        dd($type);
        $type = $type === 'mixed' ? ['video', 'photo'] : [$type];
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





    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    /**
     * model boot method
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * model saved method
         *
         * @param $model
         */
        parent::saved(function($model)
        {
            $ids = Request::get('media_id');
            $ids = is_string($ids) ? explode(',',$ids) : ( is_null($ids) ? [] : $ids);
            $model->medias()->sync( $ids );
        });
    }
}
