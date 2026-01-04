<?php

namespace Domain\Order\Services\Payment\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PaymentException extends BaseException
{
    protected $message = 'Ошибка во время работы с оплатой';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
