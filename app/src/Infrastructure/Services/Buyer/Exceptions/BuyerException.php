<?php

namespace Infrastructure\Services\Buyer\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BuyerException extends BaseException
{
    protected $message = 'Ошибка при работе с данными доставки пользователя';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
