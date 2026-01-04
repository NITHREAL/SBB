<?php

namespace Infrastructure\Services\Buyer\Components;

use Illuminate\Support\Str;

class BuyerTokenService
{
    protected string $token;

    protected string $tokenHeaderName;

    public function __construct()
    {
        $this->tokenHeaderName = config('api.headers.buyer');

        $this->token = $this->getHeaderToken() ?: Str::uuid();
    }

    public function getValue(): string
    {
        return $this->token;
    }

    private function getHeaderToken(): ?string
    {
        return request()->header($this->tokenHeaderName);
    }
}
