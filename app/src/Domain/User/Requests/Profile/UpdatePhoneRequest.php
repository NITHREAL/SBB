<?php

namespace Domain\User\Requests\Profile;

use Infrastructure\Http\Requests\BaseRequest;

class UpdatePhoneRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string|regex:/^[0-9]{10}+$/',
        ];
    }
}
