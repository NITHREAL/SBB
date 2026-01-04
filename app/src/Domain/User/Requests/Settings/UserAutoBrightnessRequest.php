<?php

namespace Domain\User\Requests\Settings;

use Infrastructure\Http\Requests\BaseRequest;

class UserAutoBrightnessRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'autoBrightness' => 'required|boolean',
        ];
    }
}
