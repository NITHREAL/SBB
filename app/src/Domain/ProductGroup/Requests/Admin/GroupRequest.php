<?php

namespace Domain\ProductGroup\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $currentGroup = Arr::get(request()->route()->parameters(), 'group');
        $currentId = $currentGroup ? $currentGroup->id : 0;

        return [
            'active'                => 'required|boolean',
            'mobile'                => 'required|boolean',
            'title'                 => 'required|string',
            'slug'                  => ['string', 'nullable', Rule::unique('groups')->ignore($currentId)],
            'sort'                  => 'required|integer|min:0|max:10000',
            'images'                => 'array|max:1',
            'images.*'              => 'integer|exists:attachments,id|nullable',
            'audience_id'           => 'integer|exists:audiences,id|nullable',
            'story_id'              => 'integer|exists:stories,id|nullable',
            'products'              => 'array',
            'products.*'            => 'array',
            'products.*.id'         => 'required_with:products.*|integer|exists:products,id',
            'products.*.pivot.sort' => 'integer|nullable|min:0|max:10000',
            'tags'                  => 'array',
            'tags.*'                => 'array',
            'tags.*.id'             => 'integer|nullable',
            'tags.*.text'           => 'required_with:tags.*|string',
            'tags.*.color'          => 'required_with:tags.*|string',
            'tags.*.active'         => 'required_with:tags.*|boolean',
            'tags.*.show_forced'    => 'required_with:tags.*|boolean',
            'background_image'      => 'array|max:1',
            'background_image.*'    => 'integer|exists:attachments,id|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'products.*.id.required_with' => __('validation.group.products.product_required'),
        ];
    }
}
