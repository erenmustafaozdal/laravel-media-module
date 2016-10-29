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
     * get the id of the youtube
     *
     * @return string|boolean
     */
    public function getYoutubeIdAttribute()
    {
        preg_match( '/[\\?\\&]v=([^\\?\\&]+)/', $this->video, $matches );
        if (isset($matches[1])) {
            return $matches[1];
        }
        return false;
    }

    /**
     * get the id of the vimeo
     *
     * @return string|boolean
     */
    public function getVimeoIdAttribute()
    {
        preg_match( '/\.com\/([^\\?\\&]+)/', $this->video, $matches );
        if (isset($matches[1])) {
            return $matches[1];
        }
        return false;
    }

    /**
     * get the source of the video
     *
     * @return string
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}?showinfo=0&rel=0&wmode=opaque";
        }
        return "https://player.vimeo.com/video/{$this->vimeo_id}?badge=0&byline=0&color=76BE1E&title=0";
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
     * get the embed code of the video for fullscreen
     *
     * @return string
     */
    public function getFullscreenHtmlAttribute()
    {
        return "<div class='embed-responsive embed-responsive-16by9'>
            <iframe class='embed-responsive-item' allowfullscreen src='{$this->embed_url}'></iframe>
        </div>";
    }

    /**
     * get the image code of the photo
     *
     * @return string
     */
    public function getImgAttribute()
    {
        return '<img src="http://img.youtube.com/vi/' . $this->youtube_id . '/sddefault.jpg">';
    }
}
