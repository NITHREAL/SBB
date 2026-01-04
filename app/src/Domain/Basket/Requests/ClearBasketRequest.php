<?php

namespace Domain\Basket\Requests;


use Infrastructure\Http\Requests\BaseRequest;

class ClearBasketRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'date'              => ['string', 'date_format:Y-m-d'],
            'onlyUnavailable'   => ['boolean'],
        ];
    }
}
