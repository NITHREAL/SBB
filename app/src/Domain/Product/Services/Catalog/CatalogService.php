<?php

namespace Domain\Product\Services\Catalog;

use Domain\Product\DTO\Catalog\CatalogFiltersDTO;
use Domain\Product\DTO\Catalog\CatalogParamsDTO;
use Domain\Product\Models\Product;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class CatalogService extends BaseCatalogPageService
{
    protected string $filtersCacheKeyPrefix = 'category_catalog_filters_list';

    public function __construct(CatalogFilterService $filterService)
    {
        parent::__construct($filterService);
    }

    public function getCatalogData(
        CatalogParamsDTO $catalogParamsDTO,
        CatalogFiltersDTO $catalogFiltersDTO,
        string $encodedRequestData,
    ): array {
        $data = $this->getProductsAndFiltersData($catalogParamsDTO, $catalogFiltersDTO, $encodedRequestData);

        $data['category'] = $catalogParamsDTO->getCategory();

        return $data;
    }

    protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void
    {
        $this->queryBuilder = Product::query()->categoryQuery($paramsDTO->getStoreSystemId());
    }

    protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void
    {
        if ($category = $paramsDTO->getCategory()) {
            $this->queryBuilder->whereCategoriesParentAndChildren($category->id, $category->children_system_ids);
        }
    }

    protected function setFiltersConditions(CatalogFiltersDTOInterface $filtersDTO): void
    {
        if ($filtersDTO->isNeedAvailableToday()) {
            $this->queryBuilder->whereAvailableToday();
        }

        if ($filtersDTO->isNeedForVegan()) {
            $this->queryBuilder->whereForVegan();
        }

        if ($filtersDTO->getFarmers()) {
            $this->queryBuilder->whereFarmers($filtersDTO->getFarmers());
        }
    }
}
