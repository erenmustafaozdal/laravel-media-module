<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media;

use ErenMustafaOzdal\LaravelModulesBase\Requests\BaseRequest;
use Sentinel;

class UpdateRequest extends BaseRequest
{
    /**
     * The input keys that should not be flashed on redirect.
     *
     * @var array
     */
    protected $dontFlash = ['photo','category_id'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $hackedRoute = 'admin.media.update';
        if ( ! is_null($this->segment(4))) {
            $hackedRoute = 'admin.media_category.media.update#####' .$this->segment(3);
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
        return [
            'title'             => 'max:255'
        ];
    }
}
