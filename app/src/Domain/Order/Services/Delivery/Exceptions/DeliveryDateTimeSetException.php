<?php

namespace Domain\Order\Services\Delivery\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DeliveryDateTimeSetException extends BaseException
{
    public $message = 'Интервал доставки указан не корректно';

    public $code = ResponseAlias::HTTP_BAD_REQUEST;
}
