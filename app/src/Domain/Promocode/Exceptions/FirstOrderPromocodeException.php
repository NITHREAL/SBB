<?php

namespace Domain\Promocode\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FirstOrderPromocodeException extends BaseException
{
    protected $message = 'Промокод для первого заказа пользователя не найден';

    protected $code = ResponseAlias::HTTP_NOT_FOUND;
}
