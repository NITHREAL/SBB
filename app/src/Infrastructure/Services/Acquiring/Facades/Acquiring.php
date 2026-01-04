<?php

namespace Infrastructure\Services\Acquiring\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;

/**
 * @method static GatewayResponseInterface register(array $params)
 * @method static GatewayResponseInterface registerPreAuth(array $params)
 * @method static GatewayResponseInterface deposit(array $params)
 * @method static GatewayResponseInterface refund(array $params)
 * @method static GatewayResponseInterface reverse(array $params)
 * @method static GatewayResponseInterface getOrderStatus(array $params)
 * @method static GatewayResponseInterface getClientBindings(array $params)
 */
class Acquiring extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Acquiring';
    }
}
