<?php

namespace Infrastructure\Services\Auth\RateLimiter;

use Infrastructure\Services\RateLimiter\BaseRateLimiter;

class LoginRateLimiter extends BaseRateLimiter
{


    protected string $key = 'login_from_ip';

    protected int $maxAttempts = 3;

    protected int $periodLength = 60;

    public function getKey(): string
    {
        return sprintf('%s:%s', $this->key, request()->ip());
    }
}
