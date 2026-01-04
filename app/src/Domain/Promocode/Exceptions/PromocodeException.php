<?php

namespace Domain\Promocode\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PromocodeException extends BaseException
{
    protected $message = 'Ошибка использования промокода';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
