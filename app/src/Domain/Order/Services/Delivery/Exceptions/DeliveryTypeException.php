<?php

namespace Domain\Order\Services\Delivery\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DeliveryTypeException extends BaseException
{
    public $message = 'Ошибка при выборе типа доставки';

    public $code = ResponseAlias::HTTP_BAD_REQUEST;
}
