<?php

namespace Domain\PromoAction\Services;

use Domain\Product\Models\Product;
use Domain\Product\Services\ProductCollectionService;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

readonly class PromoActionProductsService
{
    private const DEFAULT_LIMIT = 20;

    public function __construct(
        private ProductCollectionService $productCollectionService,
    ) {
    }

    public function getProductsForPromoActions(array $promoActionIds, int $productsLimit = null): Collection
    {
        $limit = $productsLimit ?? self::DEFAULT_LIMIT;

        $products = Product::query()
            ->promoActionsQuery($promoActionIds, BuyerStore::getOneCId())
            ->limit($limit)
            ->get();

        return $this->productCollectionService->getPreparedProductsCollection($products);
    }
}
