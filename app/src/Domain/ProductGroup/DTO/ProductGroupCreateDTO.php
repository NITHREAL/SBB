<?php

namespace Domain\ProductGroup\DTO;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\DTO\BaseDTO;

class ProductGroupCreateDTO extends BaseDTO
{
    public function __construct(
        private bool $active,
        private bool $site,
        private bool $mobile,
        private array $images,
        private string $title,
        private ?string $slug,
        private ?int $sort,
        private ?int $audienceId,
        private ?int $storyId,
        private ?int $backgroundImageId,
        private array $products,
        private array $tags,
    ) {
    }

    public static function make(array $data): self
    {
        $backgroundImageId = Arr::first(
            Arr::get($data, 'background_image', []),
            fn($item) => (int)$item,
        );

        return new self(
            Arr::get($data, 'active', false),
            Arr::get($data, 'site', false),
            Arr::get($data, 'mobile', false),
            Arr::get($data, 'images', []),
            Arr::get($data, 'title'),
            Arr::get($data, 'slug'),
            Arr::get($data, 'sort'),
            Arr::get($data, 'audience_id'),
            Arr::get($data, 'story_id'),
            $backgroundImageId,
            Arr::get($data, 'products', []),
            Arr::get($data, 'tags', []),
        );
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function availableForSite(): bool
    {
        return $this->site;
    }

    public function availableForMobile(): bool
    {
        return $this->mobile;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug ?? Str::slug($this->title);
    }

    public function getSort(): int
    {
        return $this->sort ?? self::DEFAULT_SORT;
    }

    public function getAudienceId(): ?int
    {
        return $this->audienceId;
    }

    public function getStoryId(): ?int
    {
        return $this->storyId;
    }

    public function getBackgroundImageId(): ?int
    {
        return $this->backgroundImageId;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
