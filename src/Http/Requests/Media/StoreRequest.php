<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media;

use App\Http\Requests\Request;
use Sentinel;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $hackedRoute = 'admin.media.store';
        if ( ! is_null($this->segment(4))) {
            $hackedRoute = 'admin.media_category.media.store#####' .$this->segment(3);
        }
        return hasPermission($hackedRoute);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $max_photo = config('laravel-media-module.media.uploads.photo.max_size');
        $mimes_photo = config('laravel-media-module.media.uploads.photo.mimes');

        $rules = [
            'title'             => 'required|max:255',
            'video'             => 'video_link|max:255'
        ];

        // photo elfinder mi
        if ($this->has('photo') && is_string($this->photo)) {
            $rules['photo'] = "elfinder_max:{$max_photo}|elfinder:{$mimes_photo}";
        } else  if (is_array($this->photo)){
            for($i = 0; $i < count($this->file('photo')); $i++) {
                $rules['photo.' . $i] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
            }
        } else {
            $rules['photo'] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
        }

        return $rules;
    }
}
