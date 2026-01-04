<?php

namespace Infrastructure\DTO;

use Domain\Product\Helpers\CatalogHelper;
use Illuminate\Support\Arr;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

abstract class BaseCatalogParamsDTO extends BaseDTO implements CatalogParamsDTOInterface
{
    private int $defaultLimit = 20;

    public function __construct(
        private readonly ?int    $limit,
        private readonly array   $sortBy,
    ) {
    }

    public function getLimit(): int
    {
        return $this->limit ?? $this->defaultLimit;
    }

    public function getSortBy(): array
    {
        $column = CatalogHelper::getSortByColumnValue(
            Arr::get($this->sortBy, 'column', 'default')
        );

        $direction = Arr::get($this->sortBy, 'dir', 'asc');

        return compact('column', 'direction');
    }

    public function getStoreSystemId(): string
    {
        return BuyerStore::getOneCId();
    }
}
