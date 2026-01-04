<?php

namespace Domain\User\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserAddressException extends BaseException
{
    protected $message = 'Ошибка во время получения избранного адреса пользователя';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
