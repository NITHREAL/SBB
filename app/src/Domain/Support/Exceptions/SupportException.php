<?php

namespace Domain\Support\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SupportException extends BaseException
{
    protected $message = 'Ошибка по время создания сообщения';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
