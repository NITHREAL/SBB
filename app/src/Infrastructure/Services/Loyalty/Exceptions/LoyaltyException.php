<?php

namespace Infrastructure\Services\Loyalty\Exceptions;

use Infrastructure\Exceptions\BaseException;

class LoyaltyException extends BaseException
{
    protected $message = 'Ошибка во время запроса к системе лояльности';

    protected $code = 400;
}
