<?php

namespace Domain\Product\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'product.slug'                  => 'string|nullable',
            'product.sort'                  => 'integer|nullable',
            'product.show_as_preorder'      => 'numeric|nullable',
            'product.vegan'                 => 'numeric|nullable',
            'product.by_points'             => 'numeric|nullable',
            'related_products'              => 'array',
            'related_products.*'            => 'array',
            'related_products.*.id'         => 'required_with:products.*|integer|exists:products,id',
            'related_products.*.pivot.sort' => 'integer|nullable|min:0|max:10000'
        ];
    }
}
