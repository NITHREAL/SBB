<?php

namespace Infrastructure\Services\Messaging;

interface MessageHandler
{
    public function handle(array $message): void;
}