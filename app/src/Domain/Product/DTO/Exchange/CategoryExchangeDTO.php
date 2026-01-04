<?php

declare(strict_types=1);

namespace Domain\Product\DTO\Exchange;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\DTO\BaseDTO;

class CategoryExchangeDTO extends BaseDTO
{
    private const DEFAULT_LEVEL = 0;

    public function __construct(
        protected readonly string $system_id,
        protected readonly ?string $parent_system_id,
        protected readonly int $level,
        protected readonly ?string $title,
        protected readonly ?string $slug,
    ) {
    }

    public static function make(array $data): self
    {
        $title = Arr::get($data, 'title');
        $slug = Arr::get($data, 'slug');

        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'parent_system_id'),
            Arr::get($data, 'level', self::DEFAULT_LEVEL),
            $title,
            $slug,
        );
    }

    public function getSystemId(): string
    {
        return $this->system_id;
    }

    public function getParentSystemId(): ?string
    {
        return $this->parent_system_id;
    }

    public function getLevel(): int
    {
        return $this->level ?? 0;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
