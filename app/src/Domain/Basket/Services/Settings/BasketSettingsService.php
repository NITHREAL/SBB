<?php

namespace Domain\Basket\Services\Settings;

use Domain\Basket\DTO\BasketSettingDTO;
use Domain\Basket\Models\Basket;
use Domain\Basket\Services\BasketService;
use Domain\Order\Helpers\Settings\OrderSettingHelper;
use Illuminate\Support\Arr;

readonly class BasketSettingsService
{
    public function __construct(
        private BasketService $basketService
    ) {
    }

    public function getSettings(): array
    {
        $settingsData = OrderSettingHelper::getOrderSettingsData();

        $basketSettings = $this->getBasketSettings($this->basketService->getBasketInstance());

        return $this->prepareSettings($settingsData, $basketSettings);
    }

    public function setSettings(BasketSettingDTO $basketSettingDTO): array
    {
        $basket = $this->basketService->getBasketInstance();

        $basket->settings = [
            OrderSettingHelper::UNAVAILABLE_PRODUCTS_SETTINGS   => $basketSettingDTO->getUnavailableSettingValue(),
            OrderSettingHelper::WEIGHT_PRODUCTS_SETTINGS        => $basketSettingDTO->getWeightSettingValue(),
            OrderSettingHelper::CHECK_TYPE_SETTINGS             => $basketSettingDTO->getCheckType(),
            OrderSettingHelper::FOR_OTHER_PERSON                => $basketSettingDTO->getOrderForOtherPersonValue(),
            OrderSettingHelper::OTHER_PERSON_NAME               => $basketSettingDTO->getOtherPersonName(),
            OrderSettingHelper::OTHER_PERSON_PHONE              => $basketSettingDTO->getOtherPersonPhone(),
        ];

        $basket->save();

        $settingsData = OrderSettingHelper::getOrderSettingsData();

        return $this->prepareSettings($settingsData, $basket->settings);
    }

    private function getBasketSettings(Basket $basket): array
    {
        if (empty($basket->settings)) {
            $basket->settings = OrderSettingHelper::getDefaultBasketOrderSettings();

            $basket->save();
        }

        return $basket->settings;
    }

    private function prepareSettings(array $settingsData, array $basketSettings): array
    {
        return Arr::map($settingsData, function ($settingsData, $key) use ($basketSettings) {
            return Arr::map($settingsData, function ($setting) use ($key, $basketSettings) {
                $value = in_array($setting['key'], [OrderSettingHelper::OTHER_PERSON_PHONE, OrderSettingHelper::OTHER_PERSON_NAME])
                    ? Arr::get($basketSettings, $setting['key'])
                    : $setting['key'] == Arr::get($basketSettings, $key);

                $result = [
                    'key'   => $setting['key'],
                    'value' => $value,
                    'label' => $setting['label'],
                ];

                if (Arr::has($setting, 'subLabel')) {
                    $result['subLabel'] = $setting['subLabel'];
                }

                return $result;
            });
        });
    }
}
