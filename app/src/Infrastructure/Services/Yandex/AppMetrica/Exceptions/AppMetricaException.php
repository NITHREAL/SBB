<?php

namespace Infrastructure\Services\Yandex\AppMetrica\Exceptions;

use Infrastructure\Exceptions\BaseException as Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class AppMetricaException extends Exception
{
    protected $code = ResponseAlias::HTTP_BAD_REQUEST;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
