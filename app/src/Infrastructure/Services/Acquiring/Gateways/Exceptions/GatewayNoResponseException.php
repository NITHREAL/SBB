<?php

namespace Infrastructure\Services\Acquiring\Gateways\Exceptions;

class GatewayNoResponseException extends GatewayException
{
    protected $message = 'Платежный шлюз не отвечает';

    protected $code = 500;
}
