<?php

namespace Infrastructure\Services\Catalog;

use Domain\Product\Services\Catalog\Filters\Checkbox\Types\AvailableTodayFilter;
use Domain\Product\Services\Catalog\Filters\Checkbox\Types\ForVeganFilter;
use Domain\Product\Services\Catalog\Filters\List\Types\FarmersFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;

abstract class BaseCatalogFilterService implements CatalogFilterServiceInterface
{
    private const DELIVERY_DATE_FILTER_GROUP = 'День заказа';
    private const PRODUCT_FEATURES_FILTER_GROUP = 'Особенности продуктов';

    private Collection $products;

    private CatalogFiltersDTOInterface $filtersDTO;

    public function getFilters(CatalogFiltersDTOInterface $filtersDTO, Builder $queryBuilder): array
    {
        $this->products = $queryBuilder->get();
        $this->filtersDTO = $filtersDTO;

        return $this->getNeededFilters();
    }

    protected function getNeededFilters(): array
    {
        return [
            $this->getDeliveryDateFilters(),
            $this->getProductFeaturesFilters(),
        ];
    }

    protected function getDeliveryDateFilters(): array
    {
        return [
            'title' => self::DELIVERY_DATE_FILTER_GROUP,
            'items' => [
                $this->getAvailableTodayFilter(),
            ],
        ];
    }

    protected function getProductFeaturesFilters(): array
    {
        return [
            'title' => self::PRODUCT_FEATURES_FILTER_GROUP,
            'items' => [
                $this->getForVeganFilter(),
            ],
        ];
    }

    private function getAvailableTodayFilter(): array
    {
        $isSelected = $this->filtersDTO->isNeedAvailableToday();

        return (new AvailableTodayFilter($isSelected, $this->products))->getFilterData();
    }

    private function getForVeganFilter(): array
    {
        $isSelected = $this->filtersDTO->isNeedForVegan();

        return (new ForVeganFilter($isSelected, $this->products))->getFilterData();
    }

    private function getFarmersFilter(): array
    {
        $selectedItems = $this->filtersDTO->getFarmers();

        return (new FarmersFilter($selectedItems, $this->products))->getFilterData();
    }
}
