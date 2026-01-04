<?php

namespace Domain\Product\Services\Search;

use Domain\Product\DTO\Search\SearchFiltersDTO;
use Domain\Product\DTO\Search\SearchParamsDTO;
use Domain\Product\Models\Product;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class SearchService extends BaseCatalogPageService
{
    private int $defaultSearchLimit = 20;

    protected string $filtersCacheKeyPrefix = 'product_search_filters_list';

    public function __construct(
        SearchFilterService $filterService,
    ) {
        parent::__construct($filterService);
    }

    public function getSearchData(
        SearchParamsDTO $paramsDTO,
        SearchFiltersDTO $filtersDTO,
        string $encodedRequestData,
    ): array {

        $data = $this->getProductsAndFiltersData($paramsDTO, $filtersDTO, $encodedRequestData);

        $data['search'] = $paramsDTO->getSearch();

        return $data;
    }

    protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void
    {
        $this->queryBuilder = Product::query()->smallProductCardsQuery($paramsDTO->getStoreSystemId());
    }


    protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void
    {
        if ($paramsDTO->getSearch()) {
            $this->queryBuilder->whereSearch($paramsDTO->getSearch());
        }
    }
}
