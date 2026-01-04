<?php

namespace Infrastructure\Services\Auth\Exceptions;

use Infrastructure\Exceptions\BaseException;

class AuthenticationException extends BaseException
{
    protected $message = 'Ошибка авторизации';

    protected $code = 401;
}
