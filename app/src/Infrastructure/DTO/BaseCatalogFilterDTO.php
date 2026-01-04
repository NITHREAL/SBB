<?php

namespace Infrastructure\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;

abstract class BaseCatalogFilterDTO extends BaseDTO implements CatalogFiltersDTOInterface
{
    public function __construct(
        private readonly bool  $needAvailableToday,
        private readonly bool  $needForVegan,
        private readonly array $farmers,
    ) {
    }

    public static function make(array $data): static
    {
        return new static(
            Arr::get($data, 'available_today', false),
            Arr::get($data, 'for_vegan', false),
            Arr::get($data, 'farmers', []),
        );
    }

    public function isNeedAvailableToday(): bool
    {
        return $this->needAvailableToday;
    }

    public function isNeedForVegan(): bool
    {
        return $this->needForVegan;
    }

    public function getFarmers(): array
    {
        return $this->farmers;
    }
}
