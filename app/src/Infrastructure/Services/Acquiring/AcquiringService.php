<?php

namespace Infrastructure\Services\Acquiring;

use Infrastructure\Services\Acquiring\Gateways\GatewayInterface;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;

readonly class AcquiringService
{
    public function __construct(
        private GatewayInterface $gateway,
    ) {
    }

    public function register(array $params): GatewayResponseInterface
    {
        return $this->gateway->register($params);
    }

    public function registerPreAuth(array $params): GatewayResponseInterface
    {
        return $this->gateway->registerPreAuth($params);
    }

    public function registerPreAuthAuto(array $params): GatewayResponseInterface
    {
        return $this->gateway->registerPreAuthAuto($params);
    }

    public function registerSbp(array $params): GatewayResponseInterface
    {
        return $this->gateway->registerSbp($params);
    }

    /**
     * @param array $params
     * @return GatewayResponseInterface
     */
    public function getOrderStatus(array $params): GatewayResponseInterface
    {
        return $this->gateway->getOrderStatusExtended($params);
    }

    public function deposit(array $params): GatewayResponseInterface
    {
        return $this->gateway->deposit($params);
    }

    public function refund(array $params): GatewayResponseInterface
    {
        return $this->gateway->refund($params);
    }

    public function reverse(array $params): GatewayResponseInterface
    {
        return $this->gateway->reverse($params);
    }

    public function decline(array $params): GatewayResponseInterface
    {
        return $this->gateway->decline($params);
    }
}
