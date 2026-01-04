<?php

namespace Infrastructure\Services\SMS\Sender;

interface SmsApiInterface
{
    public function __construct(array $config);

    public function send(array $params): array;
}
