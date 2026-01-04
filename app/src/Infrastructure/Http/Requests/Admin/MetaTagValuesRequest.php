<?php

namespace Infrastructure\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MetaTagValuesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'meta_tag_values.title' => ['string', 'nullable'],
            'meta_tag_values.description' => ['string', 'nullable'],
            'meta_tag_values.keywords' => ['string', 'nullable'],
            'meta_tag_values.header_one' => ['string', 'nullable']
        ];
    }
}
