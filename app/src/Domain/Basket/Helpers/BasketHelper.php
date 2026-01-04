<?php

namespace Domain\Basket\Helpers;

use Illuminate\Support\Str;

class BasketHelper
{
    public static function makeToken(): string
    {
        return Str::uuid();
    }
}
