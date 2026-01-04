<?php

namespace Domain\Faq\Requests\Admin;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class FaqCategoryRequest extends BaseRequest
{
    public function rules(): array
    {
        $categoryId = (int) Arr::get(request()->route()->parameters(), 'category', 0);

        return [
            'title'     => ['required', 'string'],
            'slug'      => [
                'nullable',
                'string',
                Rule::unique('faq_categories', 'slug')->ignore($categoryId)
            ],
            'sort'      => ['nullable', 'integer', 'min:0', 'max:1000'],
            'active'    => ['required', 'boolean'],
        ];
    }
}
