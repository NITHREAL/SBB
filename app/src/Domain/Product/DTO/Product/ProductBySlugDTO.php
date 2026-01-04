<?php

namespace Domain\Product\DTO\Product;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class ProductBySlugDTO extends BaseDTO
{
    public function __construct(
        private readonly string  $slug,
        private readonly ?int    $relatedProductsLimit,
        private readonly ?User   $user,
    ) {
    }

    public static function make(array $data, string $slug, ?User $user): self
    {
        return new self(
            $slug,
            Arr::get($data, 'relatedProductsLimit'),
            $user,
        );
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getRelatedProductsLimit(): ?int
    {
        return $this->relatedProductsLimit;
    }

    public function getStore1CId(): string
    {
        return BuyerStore::getOneCId();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
