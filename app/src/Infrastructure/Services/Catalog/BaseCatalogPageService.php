<?php

namespace Infrastructure\Services\Catalog;

use Domain\Product\Services\ProductCollectionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;

abstract class BaseCatalogPageService
{
    protected Builder $queryBuilder;

    protected string $filtersCacheKeyPrefix = 'filters_list';

    protected int $filtersCacheTtl = 10800;

    private ProductCollectionService $productCollectionService;

    public function __construct(
        private readonly CatalogFilterServiceInterface $filterService,
    ) {
        $this->productCollectionService = app()->make(ProductCollectionService::class);
    }

    protected function getProductsAndFiltersData(
        CatalogParamsDTOInterface $catalogParamsDTO,
        CatalogFiltersDTOInterface $catalogFiltersDTO,
        string $encodedRequestData,
    ): array {
        $this->setQueryBuilder($catalogParamsDTO, $catalogFiltersDTO);

        $products = $this->getProductsData($catalogParamsDTO);
        $filters = $this->getFiltersData($catalogFiltersDTO, $encodedRequestData);

        return compact('products', 'filters');
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

    abstract protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void;

    abstract protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void;

    private function getProductsData(CatalogParamsDTOInterface $paramsDTO): LengthAwarePaginator
    {
        $products = $this->getProducts($paramsDTO);

        return $this->productCollectionService->getPreparedPaginatedProductsCollection($products);
    }

    private function getFiltersData(
        CatalogFiltersDTOInterface $catalogFiltersDTO,
        string $encodedRequestParams,
    ): array {
        return Cache::remember(
            $this->getFiltersCacheKey($encodedRequestParams),
            $this->filtersCacheTtl,
            fn() => $this->filterService->getFilters($catalogFiltersDTO, $this->queryBuilder),
        );
    }

    private function getProducts(CatalogParamsDTOInterface $paramsDTO): LengthAwarePaginator
    {
        return $this->queryBuilder
            ->addSorting($paramsDTO->getSortBy())
            ->paginate($paramsDTO->getLimit());
    }

    private function setQueryBuilder(
        CatalogParamsDTOInterface  $paramsDTO,
        CatalogFiltersDTOInterface $filtersDTO,
    ): void {
        $this->setProductsQueryBuilder($paramsDTO);
        $this->setParamsConditions($paramsDTO);
        $this->setFiltersConditions($filtersDTO);
    }

    private function getFiltersCacheKey(string $encodedRequestParams): string
    {
        return sprintf('%s_%s', $this->filtersCacheKeyPrefix, md5($encodedRequestParams));
    }
}
