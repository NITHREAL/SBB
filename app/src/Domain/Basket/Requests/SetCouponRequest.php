<?php

namespace Domain\Basket\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class SetCouponRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'coupon' => 'required|string',
        ];
    }
}
