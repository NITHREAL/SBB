<?php

namespace Domain\Order\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SbermarketException extends BaseException
{
    protected $message = 'Ошибка во время обработки запроса из сбермаркета';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
