<?php

namespace Domain\User\Requests\LoyaltyCards;

use Infrastructure\Http\Requests\BaseRequest;

class LoyaltyCardRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'cardNumber' => 'required|string|size:13',
        ];
    }
}
