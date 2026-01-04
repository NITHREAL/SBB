<?php

namespace Domain\Product\DTO\Product;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class ProductByBarcodeDTO extends BaseDTO
{
    private const DEFAULT_RELATED_PRODUCTS_LIMIT = 8;

    public function __construct(
        private readonly string $barcode,
        private readonly ?int    $relatedProductsLimit,
        private readonly ?User  $user,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'barcode'),
            Arr::get($data, 'relatedProductsLimit') ?? self::DEFAULT_RELATED_PRODUCTS_LIMIT,
            Arr::get($data, 'user'),
        );
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function getStore1CId(): string
    {
        return BuyerStore::getOneCId();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }


    public function getRelatedProductsLimit(): int
    {
        return $this->relatedProductsLimit;
    }
}
