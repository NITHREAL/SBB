<?php

namespace Domain\Basket\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class ClearBasketDTO extends BaseDTO
{
    public function __construct(
        private readonly ?string $date,
        private readonly bool $onlyUnavailable,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'date'),
            Arr::get($data, 'onlyUnavailable', false),
        );
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getIsOnlyUnavailable(): bool
    {
        return $this->onlyUnavailable;
    }
}
