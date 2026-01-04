<?php

namespace Infrastructure\Services\Buyer\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\BuyerDeliveryTypeService;

/**
 * @mixin BuyerDeliveryTypeService
 * @method string|null getValue()
 * @method void setValue(string $value)
 */
class BuyerDeliveryType extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerDeliveryType';
    }
}
