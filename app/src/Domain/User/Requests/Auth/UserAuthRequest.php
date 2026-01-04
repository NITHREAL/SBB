<?php

namespace Domain\User\Requests\Auth;

use Illuminate\Support\Facades\App;
use Infrastructure\Enum\EnvironmentType;
use Infrastructure\Http\Requests\BaseRequest;
use Infrastructure\Http\Rules\Recaptcha;

class UserAuthRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $rules =  [
            'phone' => 'required|string|regex:/^[0-9]{10}+$/',
        ];

        if (app()->environment(EnvironmentType::prod()->value)) {
            $rules['g-recaptcha-response'] = ['required', App::make(Recaptcha::class)];
        }

        return $rules;
    }
}
