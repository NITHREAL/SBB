<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class GetLevelsDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'sessionId'),
        );
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}
