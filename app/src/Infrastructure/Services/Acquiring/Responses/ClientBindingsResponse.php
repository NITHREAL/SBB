<?php

namespace Infrastructure\Services\Acquiring\Responses;

class ClientBindingsResponse extends GatewayResponse
{
    /**
     * @var array - Список связок клиент/карта для клиента
     */
    public array $bindings = [];
}
