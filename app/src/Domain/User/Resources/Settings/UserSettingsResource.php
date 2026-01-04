<?php

namespace Domain\User\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSettingsResource extends JsonResource
{
    public function toArray($request): array
    {
        $settings = $this->resource;

        return [
            'allowNotify'         => (bool) $settings->allow_notify,
            'allowNotifyPush'     => (bool) $settings->allow_notify_push,
            'allowNotifyEmail'    => (bool) $settings->allow_notify_email,
            'allowNotifySms'      => (bool) $settings->allow_notify_sms,
            'allowPhoneCalls'     => (bool) $settings->allow_phone_calls,
        ];
    }
}
