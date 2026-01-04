<?php

namespace Domain\Promocode\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class GetPromocodesRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'mobile'    => 'boolean',
        ];
    }
}
