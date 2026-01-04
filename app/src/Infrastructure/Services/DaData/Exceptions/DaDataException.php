<?php

namespace Infrastructure\Services\DaData\Exceptions;

use Infrastructure\Exceptions\BaseException;

class DaDataException extends BaseException
{
    protected $message = 'Ошибка загрузки данных dadata';

    protected $code = 500;
}
