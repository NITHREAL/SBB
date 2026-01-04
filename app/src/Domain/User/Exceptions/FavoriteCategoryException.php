<?php

namespace Domain\User\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FavoriteCategoryException extends BaseException
{
    protected $message = 'Ошибка при получении любимых категорий пользователя';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
