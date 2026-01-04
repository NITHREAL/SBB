<?php

namespace Domain\User\Requests\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;
use Infrastructure\Rules\SignatureRule;

class UpdatePhoneCheckCodeRequest extends BaseRequest
{
    public function rules(): array
    {
        $user = Auth::user();

        return [
            'phone'     => [
                'required',
                'string',
                'regex:/^[0-9]{10}+$/',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'code'      => 'required|string|regex:/^(.{4})$/',
            'signature' => ['required', 'string', new SignatureRule()],
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'Такой номер телефона уже зарегистрирован!',
        ];
    }
}
