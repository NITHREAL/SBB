<?php

namespace Domain\Store\Helpers;

class StoreHelper
{
    public static function getStorePreparedAddress(string $cityTitle, string $storeTitle): string
    {
        return sprintf('г %s, %s', $cityTitle, $storeTitle);
    }
}
