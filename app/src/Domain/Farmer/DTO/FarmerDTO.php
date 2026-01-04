<?php

namespace Domain\Farmer\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class FarmerDTO extends BaseDTO
{
    public function __construct(
        private readonly ?string $oneCId,
        private readonly ?bool $active,
        private readonly ?string $name,
        private readonly ?string $supplyDescription,
        private readonly ?string $description,
        private readonly ?string $address,
        private readonly ?string $review_info,
        private readonly ?float $rating,
        private readonly ?int $sort,
        private readonly ?string $slug,
        private readonly ?array $certificates,
        private readonly ?array $metaTagValues,
        private readonly ?int $image,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, '1c_id'),
            Arr::get($data, 'active'),
            Arr::get($data, 'name'),
            Arr::get($data, 'supply_description'),
            Arr::get($data, 'description'),
            Arr::get($data, 'address'),
            Arr::get($data, 'review_info'),
            Arr::get($data, 'rating'),
            Arr::get($data, 'sort', 500),
            Arr::get($data, 'slug'),
            Arr::get($data, 'certificates', []),
            Arr::get($data, 'meta_tag_values', []),
            Arr::get($data, 'images'),
        );
    }

    public function getOneCId(): ?string
    {
        return $this->oneCId;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSupplyDescription(): ?string
    {
        return $this->supplyDescription;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function getReviewInfo(): ?string
    {
        return $this->review_info;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getCertificates(): ?array
    {
        return $this->certificates;
    }

    public function getMetaTagValues(): ?array
    {
        return $this->metaTagValues;
    }

    public function getImage(): ?int
    {
        return $this->image;
    }
}
