<?php

namespace Infrastructure\Services\DaData\DTO;

use Infrastructure\DTO\BaseDTO;

class DaDataPromptDTO extends BaseDTO
{
    public function __construct(
        private readonly string $address,
        private readonly int $count,
        private readonly array $locations,
        private readonly array $fromBound, // Гранулярные подсказки (детализация строки результата)
        private readonly array $toBound, // Гранулярные подсказки (детализация строки результата)
        private readonly array $locationsBoost = [],
        private readonly array $locationsGeo = [],
    ) {
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getLocations(): array
    {
        return $this->locations;
    }

    public function getFromBound(): array
    {
        return $this->fromBound;
    }

    public function getToBound(): array
    {
        return $this->toBound;
    }

    public function getLocationsBoost(): array
    {
        return $this->locationsBoost;
    }

    public function getLocationsGeo(): array
    {
        return $this->locationsGeo;
    }
}
