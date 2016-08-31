<?php

namespace ErenMustafaOzdal\LaravelMediaModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class MediaPhoto extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media_photos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'photo' ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['media'];
    public $timestamps = false;





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the photo of the media.
     */
    public function media()
    {
        return $this->belongsTo('App\Media');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * get the source of the video
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getPhoto([],'normal',true,'media','media');
    }

    /**
     * get the embed code of the video
     *
     * @return string
     */
    public function getHtmlAttribute()
    {
        return $this->getPhoto([],'normal',false,'media','media');
    }
}
