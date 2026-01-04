<?php

declare(strict_types=1);

namespace Domain\BonusLevel\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class BonusLevelDTO extends BaseDTO
{
    public function __construct(
        private readonly int $number,
        private readonly string $title,
        private readonly int $minBonusPoints,
        private readonly ?int $maxBonusPoints,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'number'),
            Arr::get($data, 'title'),
            Arr::get($data, 'min_bonus_points'),
            Arr::get($data, 'max_bonus_points'),
        );
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMinBonusPoints(): int
    {
        return $this->minBonusPoints;
    }

    public function getMaxBonusPoints(): ?int
    {
        return $this->maxBonusPoints;
    }
}
