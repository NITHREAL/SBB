<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class AddVirtualCardToContactDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $contactId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'sessionId'),
            Arr::get($data, 'contactId'),
        );
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }
}
