<?php

namespace ErenMustafaOzdal\LaravelMediaModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class MediaExtra extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media_category_columns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','type' ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['mediaCategory'];
    public $timestamps = false;





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the media category of the column.
     */
    public function mediaCategory()
    {
        return $this->belongsTo('App\MediaCategory');
    }

    /**
     * Get the medias of the media extra columns.
     */
    public function medias()
    {
        return $this->belongsToMany('App\Media','media_media_category_column','column_id','media_id')
            ->withPivot('value');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
