<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class GetContactDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $userId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'sessionId'),
            Arr::get($data, 'userId'),
        );
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
