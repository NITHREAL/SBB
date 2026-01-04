<?php

namespace Domain\User\Requests\Auth;

use Infrastructure\Http\Requests\BaseRequest;
use Infrastructure\Rules\SignatureRule;

class UserCheckAuthRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone'     => 'required|string|regex:/^[0-9]{10}+$/',
            'code'      => 'required|string|regex:/^(.{4})$/',
            'newsSubscription' => 'boolean',
            //'signature' => ['required', 'string', new SignatureRule()],
        ];
    }
}
