<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Requests\MediaCategory;

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
        $hackedRoute = 'admin.media_category.store';
        if ( ! is_null($this->segment(4))) {
            $hackedRoute = 'admin.media_category.media_category.store#####' .$this->segment(3);
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
        $rules = [
            'name'          => 'required|max:255',
            'type'          => 'required|in:photo,video,mixed',
            'gallery_type'  => 'required|in:classical,modern,categorization',
            'parent'        => 'integer',
            'photo_width'   => 'numeric',
            'photo_height'  => 'numeric',
        ];

        // group number rules extend
        if ($this->has('group-thumbnail') && is_array($this->get('group-thumbnail'))) {
            for ($i = 0; $i < count($this->get('group-thumbnail')); $i++) {
                $rules['group-thumbnail.' . $i . '.thumbnail_slug'] = 'alpha_dash|max:255';
                $rules['group-thumbnail.' . $i . '.thumbnail_width'] = 'numeric';
                $rules['group-thumbnail.' . $i . '.thumbnail_height'] = 'numeric';
            }
        }

        return $rules;
    }
}
