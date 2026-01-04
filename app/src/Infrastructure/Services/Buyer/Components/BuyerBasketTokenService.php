<?php

namespace Infrastructure\Services\Buyer\Components;

use Illuminate\Support\Str;
use Infrastructure\Services\Buyer\BuyerDataService;

class BuyerBasketTokenService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'basket_token';

    public function getDefaultValue(): string
    {
        return Str::uuid();
    }
}
