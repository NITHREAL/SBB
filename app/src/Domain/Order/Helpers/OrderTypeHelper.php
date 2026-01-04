<?php

namespace Domain\Order\Helpers;

use Domain\Order\Enums\OrderSourceEnum;

class OrderTypeHelper
{
    public static function getOfflineTypeSources(): array
    {
        return [
            OrderSourceEnum::offline()->value,
        ];
    }

    public static function getOnlineTypeSources(): array
    {
        return [
            OrderSourceEnum::site()->value,
            OrderSourceEnum::mobile()->value,
            OrderSourceEnum::sbermarket()->value,
        ];
    }
}
