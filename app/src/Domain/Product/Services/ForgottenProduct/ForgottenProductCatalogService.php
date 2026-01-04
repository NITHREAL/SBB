<?php

namespace Domain\Product\Services\ForgottenProduct;

use Domain\Product\DTO\Product\ForgottenProductFiltersDTO;
use Domain\Product\DTO\Product\ForgottenProductParamsDTO;
use Domain\Product\Models\ForgottenProduct;
use Domain\Product\Services\Catalog\CatalogFilterService;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class ForgottenProductCatalogService extends BaseCatalogPageService
{
    public function __construct(CatalogFilterService $filterService)
    {
        parent::__construct($filterService);
    }

    public function getCatalogData(
        ForgottenProductParamsDTO $popularProductParamsDTO,
        ForgottenProductFiltersDTO $popularProductFiltersDTO,
        string $encodedRequestData,
    ): array {
        return $this->getProductsAndFiltersData($popularProductParamsDTO, $popularProductFiltersDTO, $encodedRequestData);
    }

    protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void
    {
        $this->queryBuilder = ForgottenProduct::query()->catalogQuery($paramsDTO->getStoreSystemId());
    }

    protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void
    {}
}
