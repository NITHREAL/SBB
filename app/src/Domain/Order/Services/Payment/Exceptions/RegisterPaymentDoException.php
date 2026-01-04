<?php

namespace Domain\Order\Services\Payment\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisterPaymentDoException extends BaseException
{
    protected $message = 'Ошибка при попытке списать средства';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
