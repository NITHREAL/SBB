<?php

namespace Infrastructure\Services\Auth\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class InvalidSignatureException extends BaseException
{
    protected $message = 'Код неверен';

    protected $code = ResponseAlias::HTTP_UNAUTHORIZED;
}
