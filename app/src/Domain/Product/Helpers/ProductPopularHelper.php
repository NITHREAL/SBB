<?php

namespace Domain\Product\Helpers;

class ProductPopularHelper
{
    public static function getIntervalTimeForCalculating(): array
    {
        return [
            'from' => now()->subDay(),
            'to' => now(),
        ];
    }
}
