<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Requests\Media;

use ErenMustafaOzdal\LaravelModulesBase\Requests\BaseRequest;
use Sentinel;

class ApiUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return hasPermission('api.media.update');
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
