<?php

namespace Domain\Basket\Facades;

use Domain\Basket\Services\BasketSync\BasketSyncService;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin BasketSyncService
 */
class BasketSync extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BasketSync';
    }
}
