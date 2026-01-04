<?php

namespace Infrastructure\Services\Acquiring\Exceptions;


use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AcquiringException extends BaseException
{
    protected $message = 'Ошибка во время работы с экварингом';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
