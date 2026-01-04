<?php

namespace Domain\CouponCategory\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class CouponCategoryDTO extends BaseDTO
{
    public function __construct(
        private readonly string $title,
        private readonly ?string $description,
        private readonly ?string $purchaseTerms,
        private readonly int $price,
        private readonly int $sort,
        private readonly bool $active,
        private readonly ?int $mainImageId,
        private readonly array $images,
    ) {
    }

    public static function make(array $data): self
    {
        $images = Arr::get($data, 'images', []);
        $mainImageId = Arr::first(Arr::get($data, 'mainImage', []));

        return new self(
            Arr::get($data, 'title'),
            Arr::get($data, 'description'),
            Arr::get($data, 'purchase_terms'),
            Arr::get($data, 'price', 0),
            Arr::get($data, 'sort') ?? self::DEFAULT_SORT,
            Arr::get($data, 'active') ?? false,
            $mainImageId,
            $images,
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPurchaseTerms(): ?string
    {
        return $this->purchaseTerms;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getMainImageId(): ?int
    {
        return $this->mainImageId;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
