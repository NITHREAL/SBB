<?php

namespace Domain\Faq\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FaqException extends BaseException
{
    protected $message = 'Ошибка при получении вопроса';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
