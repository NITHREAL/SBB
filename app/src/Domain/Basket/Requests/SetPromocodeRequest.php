<?php

namespace Domain\Basket\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class SetPromocodeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'promocode' => 'required|string',
        ];
    }
}
