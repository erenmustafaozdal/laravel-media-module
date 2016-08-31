<?php

namespace ErenMustafaOzdal\LaravelMediaModule;

use Illuminate\Database\Eloquent\Model;

class MediaVideo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media_videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'video' ];

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
     * Get the video of the media.
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
    public function getEmbedAttribute()
    {
        preg_match( '/[\\?\\&]v=([^\\?\\&]+)/', $this->video, $matches );
        return "<div class='embed-responsive embed-responsive-16by9'>
            <iframe class='embed-responsive-item' src='https://www.youtube.com/embed/{$matches[1]}?showinfo=0'></iframe>
        </div>";
     }
}
