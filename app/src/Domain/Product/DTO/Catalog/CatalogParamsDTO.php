<?php

namespace Domain\Product\DTO\Catalog;

use Domain\Product\Models\Category;
use Domain\Product\Services\Category\CategorySelection;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseCatalogParamsDTO;

class CatalogParamsDTO extends BaseCatalogParamsDTO
{
    private Category $category;

    public function __construct(
        ?int $limit,
        array $sortBy,
        Category $category,
    ) {
        parent::__construct($limit, $sortBy);

        $this->category = $category;
    }

    public static function make(array $data, string $slug): self
    {
        return new static(
            Arr::get($data, 'limit'),
            Arr::get($data, 'sort', []),
            CategorySelection::getCategoryBySlug($slug),
        );
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
