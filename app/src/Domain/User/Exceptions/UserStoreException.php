<?php

namespace Domain\User\Exceptions;

use Illuminate\Support\Facades\Log;
use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserStoreException extends BaseException
{
    public function __construct(
        private readonly string $storeId,
        private readonly string $userId,
    ) {
        parent::__construct();
    }

    protected $message = 'Магазин не найден или недоступен';

    protected $code = ResponseAlias::HTTP_BAD_REQUEST;

    public function report(): void
    {
        Log::error(sprintf(
            '%s %u %s %u',
            'Пользоатель с ID',
            $this->userId,
            'пытался добавить в избранное не найденный или недоступный магазин с ID',
            $this->storeId,
        ));
    }
}
