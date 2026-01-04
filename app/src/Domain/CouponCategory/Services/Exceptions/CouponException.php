<?php

namespace Domain\CouponCategory\Services\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CouponException extends BaseException
{
    public $message = "Ошибка при попытке использовать купон";

    public $code = ResponseAlias::HTTP_BAD_REQUEST;
}
