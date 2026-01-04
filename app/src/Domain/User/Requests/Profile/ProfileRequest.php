<?php

namespace Domain\User\Requests\Profile;

use App\Rules\OnlyCyrillicSymbols;
use Domain\User\Enums\UserSexEnum;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class ProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'firstName'  => [
                'required',
                'string',
                new OnlyCyrillicSymbols()
            ],
            'middleName' => [
                'nullable',
                'string',
                new OnlyCyrillicSymbols()
            ],
            'lastName'   => [
                'nullable',
                'string',
                new OnlyCyrillicSymbols()
            ],
            'birthdate'  => [
                'nullable',
                'string',
                'date_format:Y-m-d',
            ],
            'email'      => [
                'nullable',
                'string',
                'email',
            ],
            'sex'        => [
                'string',
                Rule::in(UserSexEnum::toValues()),
            ],
        ];
    }
}
