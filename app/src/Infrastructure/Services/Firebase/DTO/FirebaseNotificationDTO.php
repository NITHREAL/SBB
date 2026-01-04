<?php

namespace Infrastructure\Services\Firebase\DTO;

use Infrastructure\DTO\BaseDTO;

class FirebaseNotificationDTO extends BaseDTO
{
    public function __construct(
        private readonly string $title,
        private readonly string $body,
        private readonly array $data,
        private readonly array $deviceTokens,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getDeviceTokens(): array
    {
        return $this->deviceTokens;
    }
}
