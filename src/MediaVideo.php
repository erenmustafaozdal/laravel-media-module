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
    public function getUrlAttribute()
    {
        return $this->video;
    }

    /**
     * get the id of the video
     *
     * @return string
     */
    public function getVideoIdAttribute()
    {
        preg_match( '/[\\?\\&]v=([^\\?\\&]+)/', $this->video, $matches );
        return $matches[1];
    }

    /**
     * get the source of the video
     *
     * @return string
     */
    public function getEmbedUrlAttribute()
    {
        return "https://www.youtube.com/embed/{$this->video_id}?showinfo=0";
    }

    /**
     * get the embed code of the video
     *
     * @return string
     */
    public function getHtmlAttribute()
    {
        return "<div class='embed-responsive embed-responsive-16by9'>
            <iframe class='embed-responsive-item' src='{$this->embed_url}'></iframe>
        </div>";
    }

    /**
     * get the image code of the photo
     *
     * @return string
     */
    public function getImgAttribute()
    {
        return '<img src="http://img.youtube.com/vi/' . $this->video_id . '/sddefault.jpg">';
    }
}
