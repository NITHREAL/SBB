<?php

namespace Infrastructure\Services\Buyer\Components;

use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Infrastructure\Services\Buyer\BuyerDataService;

class BuyerDeliverySubTypeService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'delivery_sub_type';

    public function setValue(string|array $value): void
    {
        if (!in_array($value, $this->getAvailableValues())) {
            $value = $this->getDefaultValue();
        }

        $this->setCachedValue($value);
    }

    protected function getDefaultValue(): string
    {
        return PickupTypeEnum::today()->value;
    }

    private function getAvailableValues(): array
    {
        return array_unique(array_merge(PolygonDeliveryTypeEnum::toValues(), PickupTypeEnum::toValues()));
    }
}
