<?php

namespace Infrastructure\Services\Buyer\Facades;

use Domain\City\Models\City;
use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\City\BuyerCityService;

/**
 * @mixin BuyerCityService
 * @method string|null getValue()
 * @method void setValue(string $value)
 * @method City|null getSelectedCity()
 */
class BuyerCity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerCity';
    }
}
