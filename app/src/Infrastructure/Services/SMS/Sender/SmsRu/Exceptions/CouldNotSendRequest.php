<?php

namespace Infrastructure\Services\SMS\Sender\SmsRu\Exceptions;

use DomainException;
use Exception;

class CouldNotSendRequest extends Exception
{
    protected $message = 'Запрос не выполнился. Не удалось установить связь с сервером';
}
