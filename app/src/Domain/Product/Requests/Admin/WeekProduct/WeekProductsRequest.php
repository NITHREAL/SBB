<?php

namespace Domain\Product\Requests\Admin\WeekProduct;

use Illuminate\Foundation\Http\FormRequest;

class WeekProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'weekProducts'               => 'array',
            'weekProducts.*'             => 'array',
            'weekProducts.*.product_id'  => 'required_with:popularProducts.*|integer|exists:products,id',
            'weekProducts.*.sort'        => 'integer|nullable|min:0|max:10000',
        ];
    }
}
