<?php

namespace Infrastructure\Services\Auth\Exceptions;

use Illuminate\Support\Facades\Log;
use Infrastructure\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class JWTException extends BaseException
{
    protected $message = 'Ошибка авторизации';

    protected $code = ResponseAlias::HTTP_UNAUTHORIZED;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        Log::error('JWTException: ' . $this->message, [
            'reason' => $previous?->getMessage(), 'code' => $this->code
        ]);
    }
}
