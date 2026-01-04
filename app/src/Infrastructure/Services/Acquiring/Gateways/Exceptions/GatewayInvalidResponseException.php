<?php

namespace Infrastructure\Services\Acquiring\Gateways\Exceptions;

class GatewayInvalidResponseException extends GatewayException
{
    protected $message = 'Не верный формат ответа платежного шлюза';

    protected $code = 500;
}
