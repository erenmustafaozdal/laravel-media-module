<?php

namespace ErenMustafaOzdal\LaravelMediaModule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;

class Media extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'is_publish'
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * query filter with id scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        // filter id
        if ($request->has('id')) {
            $query->where('id',$request->get('id'));
        }
        // filter title
        if ($request->has('title')) {
            $query->where('title', 'like', "%{$request->get('title')}%");
        }
        // filter category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($query) use($request) {
                $query->where('name', 'like', "%{$request->get('category')}%");
            });
        }
        // filter status
        if ($request->has('status')) {
            $query->where('is_publish',$request->get('status'));
        }
        // filter created_at
        if ($request->has('created_at_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('created_at_from')));
        }
        if ($request->has('created_at_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->get('created_at_to')));
        }
        return $query;
    }





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the categories of the media.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\MediaCategory')->withTimestamps();
    }

    /**
     * Get the media video.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function video()
    {
        return $this->hasOne('App\MediaVideo','media_id');
    }

    /**
     * Get the media photo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function photo()
    {
        return $this->hasOne('App\MediaPhoto','media_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * get the media type
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return is_null($this->video) ? 'photo' : 'video';
    }

    /**
     * get the html of the media
     *
     * @return string
     */
    public function getHtmlAttribute()
    {
        return ! is_null($this->video) ? $this->video->html : ( ! is_null($this->photo) ? $this->photo->html : '');
    }

    /**
     * get the img of the media
     *
     * @return string
     */
    public function getImgAttribute()
    {
        return is_null($this->video) ? $this->photo->img : $this->video->img;
    }





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
            if (Request::has('category_id')) {
                $request = Request::get('category_id');
                $refferer = explode('/', removeDomain(Request::server('HTTP_REFERER')));
                if ( is_array($request) && $request[0] != '0' ) {
                    $ids = $request;
                } else if ( is_string($request) && $request != 0) {
                    $ids = explode(',',$request);
                } else {
                    $ids = [];
                }

                if ( $refferer[1] === config('laravel-media-module.url.media_category') ) {
                    $ids[] = $refferer[2];
                    if ( ! is_null($model->categories->first()) && ! in_array($model->categories->first()->id,$ids) ) {
                        $ids[] = $model->categories->first()->id;
                    }
                }
                $model->categories()->sync( $ids );
            }

        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            $file = new FileRepository(config('laravel-media-module.media.uploads'));
            $file->deleteDirectories($model);
        });
    }
}
