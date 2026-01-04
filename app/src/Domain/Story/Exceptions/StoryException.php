<?php

namespace Domain\Story\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StoryException extends BaseException
{
    protected $message = 'Ошибка при получении истории';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
