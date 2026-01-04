<?php

namespace Domain\User\Requests\Settings;

use Infrastructure\Http\Requests\BaseRequest;

class UserElectronicChecksRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'electronicChecks' => 'required|boolean',
        ];
    }
}
