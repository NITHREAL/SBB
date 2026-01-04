<?php

namespace Infrastructure\Services\Auth\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SmsCodeAlreadySent extends Exception
{
    protected $message = 'Код уже отправлен';

    protected $code = ResponseAlias::HTTP_CONFLICT;
}
