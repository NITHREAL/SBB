<?php

namespace Domain\Unit\DTO\Exchange\OneC;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class UnitDTO extends BaseDTO
{
    public function __construct(
        private readonly string $systemId,
        private readonly string $title,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'title'),
        );
    }

    public function getSystemId(): string
    {
        return $this->systemId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
