<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media;

use ErenMustafaOzdal\LaravelModulesBase\Requests\BaseRequest;
use Sentinel;

class ApiStoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return hasPermission('api.media.store');
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
            'title'             => 'max:255',
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
