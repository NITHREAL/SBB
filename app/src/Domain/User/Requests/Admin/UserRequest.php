<?php

namespace Domain\User\Requests\Admin;

use Domain\User\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Infrastructure\Helpers\PhoneFormatterHelper;

class UserRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'user' => array_merge($this->user, [
                'phone' => PhoneFormatterHelper::unformat($this->user['phone'])
            ])
        ]);
    }

    public function rules()
    {
        $user = $this->route('user');

        return [
            'user.first_name' => 'string|nullable',
            'user.last_name' => 'string|nullable',
            'user.birthday' => 'date|nullable',
            'user.email' => [
                // 'required',
                'string',
                'nullable',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'user.phone' => [
                // 'required',
                'string',
                'nullable',
                'regex:/^[0-9]{10}+$/',
                Rule::unique(User::class, 'phone')->ignore($user),
            ]
        ];
    }
}
