<?php

namespace Domain\Basket\Exceptions;

use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BasketProductException extends BaseException
{
    protected $message = 'Ошибка при добавлении товара в корзину. Товар отсутствует или недоступен';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;
}
