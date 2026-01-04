<?php

namespace Domain\PromoAction\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class PromoActionOneRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'productsLimit' => 'integer|min:1|max:50',
        ];
    }
}
