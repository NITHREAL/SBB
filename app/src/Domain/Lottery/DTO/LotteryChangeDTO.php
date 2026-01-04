<?php

namespace Domain\Lottery\DTO;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\DTO\BaseDTO;

class LotteryChangeDTO extends BaseDTO
{
    public function __construct(
        private readonly string $title,
        private readonly ?string $description,
        private readonly string $slug,
        private readonly ?string $activeFrom,
        private readonly ?string $activeTo,
        private readonly int $sort,
        private readonly bool $active,
        private readonly ?int $imageId,
        private readonly ?int $miniImageId,
        private readonly array $products,
    ) {
    }

    public static function make(array $data): self
    {
        $products = self::prepareProducts(Arr::get($data, 'products', []));
        $imageId = Arr::first(Arr::get($data, 'images', []));
        $miniImageId = Arr::first(Arr::get($data, 'imagesMini', []));
        $title = Arr::get($data, 'title');
        $slug = Str::slug($title);

        return new self(
            $title,
            Arr::get($data, 'description'),
            $slug,
            Arr::get($data, 'active_from'),
            Arr::get($data, 'active_to'),
            Arr::get($data, 'sort') ?? self::DEFAULT_SORT,
            Arr::get($data, 'active') ?? false,
            $imageId,
            $miniImageId,
            $products,
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getActiveFrom(): ?string
    {
        return $this->activeFrom;
    }

    public function getActiveTo(): ?string
    {
        return $this->activeTo;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getImageId(): ?int
    {
        return $this->imageId;
    }

    public function getMiniImageId(): ?int
    {
        return $this->miniImageId;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    private static function prepareProducts(array $productsData): array
    {
        $products = [];

        foreach ($productsData as $productData) {
            $id = Arr::get($productData, 'id');
            $sort = Arr::get($productData, 'pivot.sort') ?? self::DEFAULT_SORT;

            $products[$id] = [
                'product_id'    => $id,
                'sort'          => $sort,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        return $products;
    }
}
