<?php

namespace Domain\Store\Accessors;

use Domain\Store\Models\ProductStore;
use Infrastructure\Helpers\WeekDayHelper;

final class DeliverySchedule
{
    public function __construct(
        private readonly ProductStore $productStore,
    ) {
    }

    public function __invoke(): array
    {
        return array_map(
            fn ($item) => WeekDayHelper::getPreparedWeekday($item),
            $this->productStore->delivery_schedule,
        );
    }
}
