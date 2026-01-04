<?php

namespace Domain\Product\Services\PopularProduct;

use Domain\Product\DTO\Product\PopularProductFiltersDTO;
use Domain\Product\DTO\Product\PopularProductParamsDTO;
use Domain\Product\Models\PopularProduct;
use Domain\Product\Services\Catalog\CatalogFilterService;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class PopularProductCatalogService extends BaseCatalogPageService
{
    public function __construct(CatalogFilterService $filterService)
    {
        parent::__construct($filterService);
    }

    public function getCatalogData(
        PopularProductParamsDTO $popularProductParamsDTO,
        PopularProductFiltersDTO $popularProductFiltersDTO,
        string $encodedRequestData,
    ): array {
        return $this->getProductsAndFiltersData($popularProductParamsDTO, $popularProductFiltersDTO, $encodedRequestData);
    }

    protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void
    {
        $this->queryBuilder = PopularProduct::query()->catalogQuery($paramsDTO->getStoreSystemId());
    }

    protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void
    {}
}
