<?php

namespace Infrastructure\Services\Buyer\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\BuyerDeliveryIntervalService;

/**
 * @mixin BuyerDeliveryIntervalService
 * @method array getValue()
 * @method void setValue(array $value)
 * @method string|null getDeliveryDateInterval(array $intervalData = null)
 * @method string|null getDeliveryTimeInterval(array $intervalData = null)
 */
class BuyerDeliveryInterval extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerDeliveryInterval';
    }
}
