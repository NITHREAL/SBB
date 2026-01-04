<?php

namespace Domain\Product\Requests\Admin\ForgottenProduct;

use Illuminate\Foundation\Http\FormRequest;

class ForgottenProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'forgottenProducts'               => 'array',
            'forgottenProducts.*'             => 'array',
            'forgottenProducts.*.product_id'  => 'required_with:popularProducts.*|integer|exists:products,id',
            'forgottenProducts.*.sort'        => 'integer|nullable|min:0|max:10000',
        ];
    }
}
