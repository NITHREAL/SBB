<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class ConfirmSMSAuthDTO extends BaseDTO
{
    public function __construct(
        private readonly string $token,
        private readonly string $code,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'token'),
            Arr::get($data, 'code'),
        );
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
