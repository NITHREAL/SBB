<?php

namespace Domain\Basket\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BasketDeliveryException extends BaseException
{
    protected $message = 'Ошибка параметров доставки корзины';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
