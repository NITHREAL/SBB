<?php

namespace Infrastructure\Services\Buyer\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\BuyerAddressService;

/**
 * @mixin BuyerAddressService
 * @method string|null getValue()
 * @method void setValue(string $value)
 */
class BuyerAddress extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerAddress';
    }
}
