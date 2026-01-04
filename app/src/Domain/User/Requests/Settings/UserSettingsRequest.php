<?php

namespace Domain\User\Requests\Settings;

use Infrastructure\Http\Requests\BaseRequest;

class UserSettingsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'allowNotify'         => 'boolean',
            'allowNotifyPush'     => 'boolean',
            'allowNotifyEmail'    => 'boolean',
            'allowNotifySms'      => 'boolean',
            'allowPhoneCalls'     => 'boolean',
        ];
    }
}
