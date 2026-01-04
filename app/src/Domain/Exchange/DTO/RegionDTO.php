<?php

declare(strict_types=1);

namespace Domain\Exchange\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class RegionDTO extends BaseDTO
{
    public function __construct(
        protected readonly string $system_id,
        protected readonly string $fias_id,
        protected readonly string $title,
        protected readonly int $sort
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'fias_id'),
            Arr::get($data, 'title'),
            Arr::get($data, 'sort') ?? self::DEFAULT_SORT
        );
    }

    public function getSystemId(): string
    {
        return $this->system_id;
    }

    public function getFiasId(): string
    {
        return $this->fias_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSort(): int
    {
        return $this->sort;
    }
}
