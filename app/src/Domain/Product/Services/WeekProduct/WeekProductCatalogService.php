<?php

namespace Domain\Product\Services\WeekProduct;

use Domain\Product\DTO\Product\WeekProductFiltersDTO;
use Domain\Product\DTO\Product\WeekProductParamsDTO;
use Domain\Product\Models\WeekProduct;
use Domain\Product\Services\Catalog\CatalogFilterService;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class WeekProductCatalogService extends BaseCatalogPageService
{
    public function __construct(CatalogFilterService $filterService)
    {
        parent::__construct($filterService);
    }

    public function getCatalogData(
        WeekProductParamsDTO $productOfTheWeekParamsDTO,
        WeekProductFiltersDTO $productOfTheWeekFiltersDTO,
        string $encodedRequestData,
    ): array {
        return $this->getProductsAndFiltersData($productOfTheWeekParamsDTO, $productOfTheWeekFiltersDTO, $encodedRequestData);
    }

    protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void
    {
        $this->queryBuilder = WeekProduct::query()->catalogQuery($paramsDTO->getStoreSystemId());
    }

    protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void
    {}
}
