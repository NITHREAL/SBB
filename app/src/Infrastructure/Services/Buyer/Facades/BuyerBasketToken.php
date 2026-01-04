<?php

namespace Infrastructure\Services\Buyer\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\BuyerBasketTokenService;

/**
 * @mixin BuyerBasketTokenService
 * @method string|null getValue()
 * @method void setValue(string $value)
 * @method void setDefaultValue()
 */
class BuyerBasketToken extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerBasketToken';
    }
}
