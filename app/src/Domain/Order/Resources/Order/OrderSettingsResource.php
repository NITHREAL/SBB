<?php

namespace Domain\Order\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OrderSettingsResource extends JsonResource
{
    public function toArray($request): array
    {
        $settings = $this->resource->settings?->toArray() ?? [];

        return [
            'unavailableSettings' => Arr::get($settings, 'unavailable_settings'),
            'weightSettings' => Arr::get($settings, 'weight_settings'),
            'orderForOtherPersonSettings' => Arr::get($settings, 'order_for_other_person_settings'),
            'otherPersonPhone' => Arr::get($settings, 'other_person_phone'),
            'otherPersonName' => Arr::get($settings, 'other_person_name'),
            'checkType' => Arr::get($settings, 'check_type'),
        ];
    }
}
