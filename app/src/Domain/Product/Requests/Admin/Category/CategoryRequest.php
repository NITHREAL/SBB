<?php

namespace Domain\Product\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Infrastructure\Http\Requests\Admin\MetaTagValuesRequest;

class CategoryRequest extends FormRequest
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
    public function rules(MetaTagValuesRequest $request)
    {
        $category = $request->route('category');

        return array_merge([
            'category.slug' => 'string|nullable|unique:categories,slug,' . $category->id,
            'category.sort' => 'integer|nullable'
        ], $request->rules());
    }
}
