<?php

namespace Infrastructure\Services\RateLimiter;

use Illuminate\Support\Facades\RateLimiter;

abstract class BaseRateLimiter
{
    protected string $key = 'default';

    protected int $maxAttempts = 3;

    protected int $periodLength = 300;

    public function attempt(): bool
    {
        return RateLimiter::attempt(
            $this->getKey(),
            $this->getMaxAttempts(),
            $this->getCallback(),
            $this->getPeriodLength(),
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function getPeriodLength(): int
    {
        return $this->periodLength;
    }

    protected function getCallback(): callable
    {
        return fn() => true;
    }
}
