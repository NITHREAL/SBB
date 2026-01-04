<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Auth;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class ConfirmSmsAuthResponse implements ManzanaResponseInterface
{
    public function __construct(
        private string $sessionId,
        private string $id,
        private string $type,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'SessionId'),
            Arr::get($data, 'Id'),
            Arr::get($data, 'Type'),
        );
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
