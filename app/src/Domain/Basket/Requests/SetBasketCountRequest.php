<?php

namespace Domain\Basket\Requests;

use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class SetBasketCountRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'productId' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'count' => [
                Rule::requiredIf(!$this->request->has('weight')),
                'integer',
                'min:1',
            ],
            'weight' => [
                Rule::requiredIf(!$this->request->has('count')),
                'decimal:0,1',
                'min:0.1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'count.required_if' => __('validation.basket.set_count.weight_or_count_message'),
            'weight.required_if' => __('validation.basket.set_count.weight_or_count_message'),
        ];
    }
}
