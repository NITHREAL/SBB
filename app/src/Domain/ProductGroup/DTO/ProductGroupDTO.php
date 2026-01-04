<?php

namespace Domain\ProductGroup\DTO;

use Illuminate\Support\Arr;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class ProductGroupDTO
{
    private int $defaultProductsLimit = 5;

    public function __construct(
        private readonly bool $mobile,
        private readonly bool $products,
        private readonly ?int $productsLimit,
        private readonly ?int $userId,
    ) {
    }

    public static function make(array $data, ?int $userId): self
    {
        return new self(
            Arr::has($data, 'mobile'),
            Arr::get($data, 'products') === 'true',
            Arr::get($data, 'limit'),
            $userId,
        );
    }

    public function getStore1cId(): ?string
    {
        return BuyerStore::getOneCId();
    }

    public function getMobile(): bool
    {
        return $this->mobile;
    }

    public function getProducts(): bool
    {
        return $this->products;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getProductsLimit(): int
    {
        return $this->productsLimit ?? $this->defaultProductsLimit;
    }
}
