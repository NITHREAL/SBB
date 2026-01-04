<?php

namespace Domain\Product\DTO\Product;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class ProductDTO extends BaseDTO
{
    public function __construct(
        private readonly string $slug,
        private readonly int    $sort,
        private readonly bool   $showAsPreorder,
        private readonly bool   $vegan,
        private readonly bool   $byPoints,
        private readonly array  $relatedProducts
    ) {
    }

    public static function make(array $data): self
    {
        $productData = Arr::get($data, 'product', []);

        return new self(
            Arr::get($productData, 'slug'),
            Arr::get($productData, 'sort', self::DEFAULT_SORT),
            Arr::get($productData, 'show_as_preorder', false),
            Arr::get($productData, 'vegan', false),
            Arr::get($productData, 'by_points', false),
            Arr::get($data, 'related_products', [])
        );
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function isShowAsPreorder(): bool
    {
        return $this->showAsPreorder;
    }

    public function isVegan(): bool
    {
        return $this->vegan;
    }

    public function isByPoints(): bool
    {
        return $this->byPoints;
    }

    public function getRelatedProducts(): array
    {
        return $this->relatedProducts;
    }
}
