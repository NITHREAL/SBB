<?php

namespace Infrastructure\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

abstract class BaseException extends Exception
{
    protected $message = 'Неизвестная ошибка';

    protected $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
}