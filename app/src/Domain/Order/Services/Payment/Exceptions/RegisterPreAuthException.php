<?php

declare(strict_types=1);

namespace Domain\Order\Services\Payment\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisterPreAuthException extends BaseException
{
    protected $message = 'Ошибка при попытке заморозить средства';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
