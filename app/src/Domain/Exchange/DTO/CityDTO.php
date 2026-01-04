<?php

declare(strict_types=1);

namespace Domain\Exchange\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class CityDTO extends BaseDTO
{
    public function __construct(
        protected readonly string $system_id,
        protected readonly string $region_system_id,
        protected readonly string $fias_id,
        protected readonly string $title,
        protected readonly bool $is_settlement,
        protected readonly ?string $timezone,
        protected readonly int $sort
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'region_system_id'),
            Arr::get($data, 'fias_id'),
            Arr::get($data, 'title'),
            (bool)Arr::get($data, 'is_settlement', false),
            Arr::get($data, 'timezone', null),
            Arr::get($data, 'sort') ?? self::DEFAULT_SORT
        );
    }

    public function getSystemId(): string
    {
        return $this->system_id;
    }

    public function getRegionSystemId(): string
    {
        return $this->region_system_id;
    }

    public function getFiasId(): string
    {
        return $this->fias_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isSettlement(): bool
    {
        return $this->is_settlement;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }
}
