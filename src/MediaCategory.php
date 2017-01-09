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
    protected $fillable = [
        'name',
        'type',
        'gallery_type',
        'has_description',
        'datatable_filter',
        'datatable_tools',
        'datatable_fast_add',
        'datatable_group_action',
        'datatable_detail',
        'description_is_editor',
        'config_propagation',
        'photo_width',
        'photo_height',
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * all medias of the category descendants and self
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllMedias($query)
    {
        return $query->with([
            'medias' => function ($q)
            {
                $q->select('id','title','description','is_publish')->with(['photo','video']);
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

    /**
     * Get the thumbnails of the media category.
     */
    public function thumbnails()
    {
        return $this->hasMany('App\MediaThumbnail','category_id');
    }

    /**
     * Get the extras of the media category.
     */
    public function extras()
    {
        return $this->hasMany('App\MediaExtra','category_id');
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
            if (Request::has('media_id')) {
                $ids = is_string(Request::get('media_id'))
                    ? explode(',',Request::get('media_id'))
                    : (
                    ! Request::get('media_id') || Request::get('media_id')[0] == 0
                        ? []
                        : Request::get('media_id')
                    );
                $model->medias()->sync( $ids );
            }

            // cache forget
            if (\Request::segment(3) == 1) \Cache::forget(implode('_',['medias','enterprise_photo'])); // kurumsal foto
            if (\Request::segment(3) == 2) \Cache::forget(implode('_',['medias','enterprise_video'])); // kurumsal video
            if (\Request::segment(3) == 3) \Cache::forget(implode('_',['medias','media_we_photo'])); // basında biz foto
            if (\Request::segment(3) == 4) \Cache::forget(implode('_',['medias','media_we_video'])); // basında biz video
        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            // cache forget
            if (\Request::segment(3) == 1) \Cache::forget(implode('_',['medias','enterprise_photo'])); // kurumsal foto
            if (\Request::segment(3) == 2) \Cache::forget(implode('_',['medias','enterprise_video'])); // kurumsal video
            if (\Request::segment(3) == 3) \Cache::forget(implode('_',['medias','media_we_photo'])); // basında biz foto
            if (\Request::segment(3) == 4) \Cache::forget(implode('_',['medias','media_we_video'])); // basında biz video
        });
    }
}
