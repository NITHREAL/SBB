<?php

namespace Domain\Product\Services;

use Domain\Product\Models\Product;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

abstract class BaseSelectionService
{
    private int $defaultLimit = 8;

    public function __construct(
        private readonly ProductCollectionService $productCollectionService
    ) {
    }

    public function getData(?int $limit): Collection
    {
        $limit = $limit ?? $this->defaultLimit;

        $selectionTableName = $this->getSelectionTableName();

        $products = $this->getProducts($limit, $selectionTableName);

        return $this->productCollectionService->getPreparedProductsCollection($products);
    }

    abstract protected function getSelectionClassName(): string;


    private function getProducts(int $limit, string $tableName): Collection
    {
        $store1cId = BuyerStore::getOneCId();

        return Product::query()
            ->productsSelectionQuery($store1cId, $limit, $tableName)
            ->get();
    }

    protected function getSelectionTableName(): string
    {
        $className = $this->getSelectionClassName();

        return app($className)->getTable();
    }
}
