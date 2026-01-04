<?php

namespace Domain\Faq\DTO;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\DTO\BaseDTO;

class FaqCategoryDTO extends BaseDTO
{
    public function __construct(
        private readonly string $title,
        private readonly ?string $slug,
        private readonly bool   $isActive,
        private readonly int    $sort,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'title'),
            Arr::get($data, 'slug'),
            Arr::get($data, 'active', false),
            Arr::get($data, 'sort') ?? self::DEFAULT_SORT,
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug ?? Str::slug($this->title);
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getSort(): int
    {
        return $this->sort;
    }
}
