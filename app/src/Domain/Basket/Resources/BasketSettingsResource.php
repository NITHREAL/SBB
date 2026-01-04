<?php

namespace Domain\Basket\Resources;

use Domain\Order\Helpers\Settings\OrderSettingHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BasketSettingsResource extends JsonResource
{
    public function toArray($request): array
    {
        $settings = $this->resource;

        $otherPersonSettings = Arr::mapWithKeys(
            Arr::get($settings, 'for_other_person'),
            function($item) {
                $key = match(Arr::get($item, 'key')) {
                    OrderSettingHelper::FOR_OTHER_PERSON    => 'other_person',
                    OrderSettingHelper::OTHER_PERSON_NAME   => 'other_person_name',
                    OrderSettingHelper::OTHER_PERSON_PHONE  => 'other_person_phone',
                };

                return [$key => Arr::get($item, 'value')];
            }
        );

        return [
            'unavailableSettings'           => Arr::get($settings, 'unavailable_settings'),
            'weightSettings'                => Arr::get($settings, 'weight_settings'),
            'checkType'                     => Arr::get($settings, 'check_type_settings'),
            'orderForOtherPersonSettings'   => Arr::get($otherPersonSettings, 'other_person'),
            'otherPersonPhone'              => Arr::get($otherPersonSettings, 'other_person_phone'),
            'otherPersonName'               => Arr::get($otherPersonSettings, 'other_person_name'),
        ];
    }
}
