<?php

namespace Infrastructure\Services\SMS\Sender;

use DateTimeInterface;

interface SmsMessageInterface
{
    public static function create(string $content = ''): static;

    public function content(string $content): static;

    public function from(string $from): static;

    public function sendAt(DateTimeInterface $sendAt = null): static;
}
