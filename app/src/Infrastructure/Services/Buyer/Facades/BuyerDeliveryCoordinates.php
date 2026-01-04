<?php

namespace Infrastructure\Services\Buyer\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\BuyerDeliveryCoordinatesService;

/**
 * @mixin BuyerDeliveryCoordinatesService
 * @method array getValue()
 * @method void setValue(array $value)
 */
class BuyerDeliveryCoordinates extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerDeliveryCoordinates';
    }
}
