<?php

namespace Domain\Product\Services\Favorite;

use Domain\Product\Models\Product;
use Domain\Product\Services\ProductCollectionService;
use Illuminate\Pagination\LengthAwarePaginator;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class FavoriteProductsService
{
    private int $defaultLimit = 20;

    public function __construct(
        private readonly FavoriteService $favoriteService,
        private readonly ProductCollectionService $productCollectionService,
    ) {
    }

    public function getFavoriteProductsData(
        int $limit = null,
        string $store1cId = null,
    ): LengthAwarePaginator {
        $store1cId = $store1cId ?? BuyerStore::getSelectedStore()->getAttribute('system_id');
        $limit = $limit ?? $this->defaultLimit;

        $productsIds = $this->favoriteService->getFavorite()
            ->products()
            ->get()
            ->pluck('id')
            ->toArray();

        return $this->getProductsData($productsIds, $limit, $store1cId);
    }

    private function getProductsData(
        array $productIds,
        int $limit,
        string $store1cId,
    ): LengthAwarePaginator {
        $products = Product::query()
            ->smallProductCardsQuery($store1cId)
            ->whereIn('products.id', $productIds)
            ->orderBy('products.sort')
            ->paginate($limit);

        return $this->productCollectionService->getPreparedPaginatedProductsCollection($products);
    }
}
