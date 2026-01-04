<?php

namespace Domain\ProductGroup\DTO\ProductGroupPage;

use Domain\ProductGroup\Models\ProductGroup;
use Domain\ProductGroup\Services\ProductGroupSelection;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseCatalogParamsDTO;

class ProductGroupPageParamsDTO extends BaseCatalogParamsDTO
{
    private ProductGroup $productGroup;

    private ?int $userId;

    public function __construct(
        ?int $limit,
        array $sortBy,
        ProductGroup $productGroup,
        ?int $userId,
    ) {
        parent::__construct(
            $limit,
            $sortBy,
        );

        $this->productGroup = $productGroup;
        $this->userId = $userId;
    }

    public static function make(array $data, string $slug, ?int $userId): self
    {
        return new static(
            Arr::get($data, 'limit'),
            Arr::get($data, 'sort', []),
            ProductGroupSelection::getProductGroupBySlug($slug),
            $userId,
        );
    }

    public function getProductGroup(): ProductGroup
    {
        return $this->productGroup;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
