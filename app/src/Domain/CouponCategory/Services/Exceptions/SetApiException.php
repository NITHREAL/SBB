<?php

namespace Domain\CouponCategory\Services\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SetApiException extends BaseException
{
    public $message = "Не удалось купить купон";

    public $code = ResponseAlias::HTTP_BAD_REQUEST;
}
