<?php

namespace Infrastructure\Services\Captcha;

interface CaptchaInterface
{
    public function __construct(array $config);

    public function send(array $value): array;
}
