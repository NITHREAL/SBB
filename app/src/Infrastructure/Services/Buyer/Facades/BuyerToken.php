<?php

namespace Infrastructure\Services\Buyer\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\BuyerTokenService;

/**
 * @mixin BuyerTokenService
 * @method string getValue()
 */
class BuyerToken extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerToken';
    }
}
