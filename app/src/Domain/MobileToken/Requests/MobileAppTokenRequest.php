<?php

namespace Domain\MobileToken\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MobileAppTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token'     => 'required|string',
            'device'    => 'string',
            'service'   => 'nullable|string',
        ];
    }
}
