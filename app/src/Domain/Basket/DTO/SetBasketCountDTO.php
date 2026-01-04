<?php

namespace Domain\Basket\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class SetBasketCountDTO extends BaseDTO
{
    public function __construct(
        private readonly ?int $count,
        private readonly ?float $weight,
        public readonly int $productId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'count', 1),
            Arr::get($data, 'weight', 0.1),
            Arr::get($data, 'productId'),
        );
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }
}
