<?php

namespace Infrastructure\Services\Acquiring\Gateways\Exceptions;

class GatewayResponsePropertyNotDefinedException extends GatewayException
{
    protected $message = 'Свойство не определено';

    protected $code = 500;
}
