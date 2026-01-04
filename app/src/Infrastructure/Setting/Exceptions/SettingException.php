<?php

namespace Infrastructure\Setting\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SettingException extends BaseException
{
    // TODO доработать чтобы в сообщении указывалось какая именно настройка не установлена
    protected $message = 'Запрашиваемая настройка не установлена';

    protected $code = ResponseAlias::HTTP_NOT_FOUND;
}
