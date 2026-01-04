<?php

namespace Domain\Lottery\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class LotteryCollectionDTO extends BaseDTO
{
    private const DEFAULT_LIMIT = 3;

    private const DEFAULT_PRODUCTS_LIMIT = 20;

    public function __construct(
        private readonly int $limit,
        private readonly int $productsLimit,
        private readonly bool $withProducts,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'limit') ?? self::DEFAULT_LIMIT,
            Arr::get($data, 'products_limit') ?? self::DEFAULT_PRODUCTS_LIMIT,
            Arr::get($data, 'products') ?? false,
        );
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getProductsLimit(): int
    {
        return $this->productsLimit;
    }

    public function isWithProducts(): bool
    {
        return $this->withProducts;
    }
}
