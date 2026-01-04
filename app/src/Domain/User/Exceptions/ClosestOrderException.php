<?php

namespace Domain\User\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ClosestOrderException extends BaseException
{
    protected $message = 'Ошибка получения ближайшего заказа';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
