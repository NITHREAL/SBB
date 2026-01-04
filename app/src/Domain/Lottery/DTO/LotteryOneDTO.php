<?php

namespace Domain\Lottery\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class LotteryOneDTO extends BaseDTO
{
    private const DEFAULT_PRODUCTS_LIMIT = 20;

    public function __construct(
        private readonly string $slug,
        private readonly int $productsLimit,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'slug'),
            Arr::get($data, 'productsLimit') ?? self::DEFAULT_PRODUCTS_LIMIT,
        );
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getProductsLimit(): int
    {
        return $this->productsLimit;
    }
}
