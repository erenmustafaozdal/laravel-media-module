<?php

namespace ErenMustafaOzdal\LaravelMediaModule\Http\Requests\MediaCategory;

use App\Http\Requests\Request;
use Sentinel;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('admin.media_category.update')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'parent'        => 'integer',
            'photo_width'   => 'numeric',
            'photo_height'  => 'numeric',
        ];

        if( $this->form === 'general' ) {
            $rules['name'] = 'required|max:255';
            $rules['type'] = 'required|in:photo,video,mixed';
            $rules['gallery_type'] = 'required|in:classical,modern,categorization';
        }

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
