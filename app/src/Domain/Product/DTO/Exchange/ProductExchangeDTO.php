<?php

declare(strict_types=1);

namespace Domain\Product\DTO\Exchange;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class ProductExchangeDTO extends BaseDTO
{
    public function __construct(
        protected readonly string $system_id,
        protected readonly string $unit_system_id,
        protected readonly ?array $categories_1c_id,
        protected readonly ?array $properties_1c_id,
        protected readonly bool $active,
        protected readonly ?string $sku,
        protected readonly string $title,
        protected readonly ?string $description,
        protected readonly ?string $composition,
        protected readonly ?string $storage_conditions,
        protected readonly ?float $proteins,
        protected readonly ?float $fats,
        protected readonly ?float $carbohydrates,
        protected readonly ?float $nutrition_kcal,
        protected readonly ?float $nutrition_kj,
        protected readonly bool $is_weight,
        protected readonly ?float $weight,
        protected readonly ?int $shelf_life,
        protected readonly bool $delivery_in_country,
        protected readonly bool $by_preorder,
        protected readonly ?array $delivery_dates,
        protected readonly bool $cooking,
        protected readonly bool $is_ready_to_eat,
        protected readonly ?array $barcodes,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'unit_system_id'),
            Arr::get($data, 'categories_1c_id', []),
            Arr::get($data, 'properties_1c_id', []),
            Arr::get($data, 'active') ?? false,
            Arr::get($data, 'sku'),
            Arr::get($data, 'title'),
            Arr::get($data, 'description'),
            Arr::get($data, 'composition'),
            Arr::get($data, 'storage_conditions'),
            Arr::get($data, 'proteins'),
            Arr::get($data, 'fats'),
            Arr::get($data, 'carbohydrates'),
            Arr::get($data, 'nutrition_kcal'),
            Arr::get($data, 'nutrition_kj'),
            Arr::get($data, 'is_weight') ?? false,
            Arr::get($data, 'weight'),
            Arr::get($data, 'shelf_life'),
            Arr::get($data, 'delivery_in_country') ?? false,
            Arr::get($data, 'by_preorder') ?? false,
            Arr::get($data, 'delivery_dates', []),
            Arr::get($data, 'cooking') ?? false,
            Arr::get($data, 'is_ready_to_eat') ?? false,
            Arr::get($data, 'barcodes'),
        );
    }

    public function getSystemId(): string
    {
        return $this->system_id;
    }

    public function getUnitSystemId(): string
    {
        return $this->unit_system_id;
    }

    public function getCategories1cId(): ?array
    {
        return $this->categories_1c_id;
    }

    public function getProperties1cId(): ?array
    {
        return $this->properties_1c_id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getComposition(): ?string
    {
        return $this->composition;
    }

    public function getStorageConditions(): ?string
    {
        return $this->storage_conditions;
    }

    public function getProteins(): ?float
    {
        return $this->proteins;
    }

    public function getFats(): ?float
    {
        return $this->fats;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function getNutritionKcal(): ?float
    {
        return $this->nutrition_kcal;
    }

    public function getNutritionKj(): ?float
    {
        return $this->nutrition_kj;
    }

    public function getIsWeight(): bool
    {
        return $this->is_weight;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getShelfLife(): ?int
    {
        return $this->shelf_life;
    }

    public function isDeliveryInCountry(): bool
    {
        return $this->delivery_in_country;
    }

    public function isByPreorder(): bool
    {
        return $this->by_preorder;
    }

    public function getDeliveryDates(): ?array
    {
        return $this->delivery_dates;
    }

    public function isCooking(): bool
    {
        return $this->cooking;
    }

    public function isReadyToEat(): bool
    {
        return $this->is_ready_to_eat;
    }

    public function getBarcodes(): ?array
    {
        return $this->barcodes;
    }
}
