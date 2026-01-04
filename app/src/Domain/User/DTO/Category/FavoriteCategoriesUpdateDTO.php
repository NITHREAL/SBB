<?php

namespace Domain\User\DTO\Category;

use Illuminate\Support\Arr;

readonly class FavoriteCategoriesUpdateDTO
{
    public function __construct(
        private array $categoryIds,
        private string $period,
        private int $userId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'categories', []),
            Arr::get($data, 'period'),
            Arr::get($data, 'userId'),
        );
    }

    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
