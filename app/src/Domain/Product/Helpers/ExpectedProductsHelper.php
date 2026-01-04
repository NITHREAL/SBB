<?php

namespace Domain\Product\Helpers;

use Illuminate\Support\Carbon;

class ExpectedProductsHelper
{
    private const EXPECTED_PRODUCT_OUTDATED_INTERVAL = 7;

    public static function getExpectedProductOutdatedInterval(): Carbon
    {
        return now()->subDays(self::EXPECTED_PRODUCT_OUTDATED_INTERVAL);
    }
}
