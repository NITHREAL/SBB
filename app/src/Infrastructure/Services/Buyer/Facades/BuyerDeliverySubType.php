<?php

namespace Infrastructure\Services\Buyer\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\BuyerDeliverySubTypeService;

/**
 * @mixin BuyerDeliverySubTypeService
 * @method string|null getValue()
 * @method void setValue(string $value)
 */
class BuyerDeliverySubType extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerDeliverySubType';
    }
}
