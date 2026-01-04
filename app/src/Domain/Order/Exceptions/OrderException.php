<?php

namespace Domain\Order\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderException extends BaseException
{
    protected $message = 'Ошибка во время создания заказа';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
