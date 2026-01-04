<?php

namespace Domain\Product\Requests\Admin\RecommendedProduct;

use Illuminate\Foundation\Http\FormRequest;

class RecommendedProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recommendedProducts'               => 'array',
            'recommendedProducts.*'             => 'array',
            'recommendedProducts.*.product_id'  => 'required_with:recommendedProducts.*|integer|exists:products,id',
            'recommendedProducts.*.sort'        => 'integer|nullable|min:0|max:10000',
        ];
    }
}
