<?php

namespace Infrastructure\Services\Auth\Exceptions;

use Infrastructure\Exceptions\BaseException;

class SmsNotifyException extends BaseException
{
    protected $message = 'Не удалось отправить sms сообщение';

    protected $code = 500;
}
