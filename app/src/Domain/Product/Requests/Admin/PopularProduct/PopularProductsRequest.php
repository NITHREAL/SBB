<?php

namespace Domain\Product\Requests\Admin\PopularProduct;

use Illuminate\Foundation\Http\FormRequest;

class PopularProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'popularProducts'               => 'array',
            'popularProducts.*'             => 'array',
            'popularProducts.*.product_id'  => 'required_with:popularProducts.*|integer|exists:products,id',
            'popularProducts.*.sort'        => 'integer|nullable|min:0|max:10000',
        ];
    }
}
