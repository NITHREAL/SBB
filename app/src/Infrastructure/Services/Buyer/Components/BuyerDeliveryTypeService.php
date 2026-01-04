<?php

namespace Infrastructure\Services\Buyer\Components;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Infrastructure\Services\Buyer\BuyerDataService;

class BuyerDeliveryTypeService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'delivery_type';

    public function setValue(string|array $value): void
    {
        if (OrderDeliveryHelper::isValidDeliveryType($value) === false) {
            $value = $this->getDefaultValue();
        }

        $this->setCachedValue($value);
    }

    protected function getDefaultValue(): string
    {
        return DeliveryTypeEnum::pickup()->value;
    }
}
