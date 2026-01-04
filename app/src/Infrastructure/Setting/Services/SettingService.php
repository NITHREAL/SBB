<?php

namespace Infrastructure\Setting\Services;

use Infrastructure\Setting\Exceptions\SettingException;
use Infrastructure\Setting\Models\Setting;

class SettingService
{
    /**
     * @throws SettingException
     */
    public function getSettingByKey(string $key): Setting
    {
        $setting =  Setting::query()
            ->select(['settings.*'])
            ->whereActive()
            ->whereSettingKey($key)
            ->first();

        if (!$setting) {
            throw new SettingException();
        }

        return $setting;
    }
}
