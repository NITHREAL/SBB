<?php

namespace Domain\Order\Helpers\Settings;

use Domain\Order\Enums\OrderSetting\CheckTypeProductOrderSettingEnum;
use Domain\Order\Enums\OrderSetting\ForOtherPersonProductOrderSettingEnum;
use Domain\Order\Enums\OrderSetting\UnavailableProductOrderSettingEnum;
use Domain\Order\Enums\OrderSetting\WeightProductOrderSettingEnum;
use Illuminate\Support\Arr;

class OrderSettingHelper
{
    public const UNAVAILABLE_PRODUCTS_SETTINGS = 'unavailable_settings';

    public const WEIGHT_PRODUCTS_SETTINGS = 'weight_settings';

    public const CHECK_TYPE_SETTINGS = 'check_type_settings';

    public const FOR_OTHER_PERSON = 'for_other_person';

    public const OTHER_PERSON_NAME = 'other_person_name';

    public const OTHER_PERSON_PHONE = 'other_person_phone';

    public static function getOrderSettingsData(): array
    {
        return [
            self::UNAVAILABLE_PRODUCTS_SETTINGS => self::getUnavailableProductsOrderSettingsData(),
            self::WEIGHT_PRODUCTS_SETTINGS      => self::getWeightProductOrderSettingsData(),
            self::CHECK_TYPE_SETTINGS           => self::getCheckTypeOrderSettingsData(),
            self::FOR_OTHER_PERSON              => self::getForOtherPersonOrderSettingsData(),
        ];
    }

    public static function getDefaultBasketOrderSettings(): array
    {
        return [
            self::UNAVAILABLE_PRODUCTS_SETTINGS => UnavailableProductOrderSettingEnum::requestAndChange()->value,
            self::WEIGHT_PRODUCTS_SETTINGS      => WeightProductOrderSettingEnum::callAndAsk()->value,
            self::CHECK_TYPE_SETTINGS           => CheckTypeProductOrderSettingEnum::electronicCheck()->value,
            self::FOR_OTHER_PERSON              => false,
            self::OTHER_PERSON_NAME             => null,
            self::OTHER_PERSON_PHONE            => null,
        ];
    }

    public static function getUnavailableProductsOrderSettingsData(): array
    {
        $settings = [
            UnavailableProductOrderSettingEnum::requestAndChange()->value,
            UnavailableProductOrderSettingEnum::noChange()->value,
            UnavailableProductOrderSettingEnum::noRequestAndChange()->value,
        ];

        return Arr::map($settings, function ($setting) {
            return                 [
                'key' => $setting,
                'label' => __('order-settings.label.unavailable.' . $setting),
                'subLabel' => __('order-settings.sub-label.unavailable.' . $setting),
            ];
        });
    }

    public static function getWeightProductOrderSettingsData(): array
    {
        $settings = [
            WeightProductOrderSettingEnum::callAndAsk()->value,
        ];

        return Arr::map($settings, function ($setting) {
            return                 [
                'key'   => $setting,
                'label' => __('order-settings.label.weight.' . $setting),
            ];
        });
    }

    public static function getCheckTypeOrderSettingsData(): array
    {
        $settings = [
            CheckTypeProductOrderSettingEnum::electronicCheck()->value,
            CheckTypeProductOrderSettingEnum::paperCheck()->value,
        ];

        return Arr::map($settings, function ($setting) {
            return [
                'key'   => $setting,
                'label' => __('order-settings.label.check.' . $setting),
            ];
        });
    }

    public static function getForOtherPersonOrderSettingsData(): array
    {
        $settings = [
            ForOtherPersonProductOrderSettingEnum::forOtherPerson()->value,
            ForOtherPersonProductOrderSettingEnum::otherPersonName()->value,
            ForOtherPersonProductOrderSettingEnum::otherPersonPhoneNumber()->value,
        ];

        return Arr::map($settings, function ($setting) {
            return [
                'key'   => $setting,
                'label' => __('order-settings.label.order_for_other_person.' . $setting),
            ];
        });
    }
}
