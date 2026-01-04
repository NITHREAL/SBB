<?php

namespace Domain\Lottery\Services;

use Domain\Product\Models\Product;
use Domain\Product\Services\ProductCollectionService;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

readonly class LotteryProductsService
{
    private const DEFAULT_LIMIT = 20;

    public function __construct(
        private ProductCollectionService $productCollectionService,
    ) {
    }

    public function getLotteryProducts(array $lotteryIds, int $productsLimit = null): Collection
    {
        $productsLimit = $productsLimit ?? self::DEFAULT_LIMIT;

        $products = Product::query()
            ->lotteriesQuery($lotteryIds, BuyerStore::getOneCId())
            ->limit($productsLimit)
            ->get();

        return $this->productCollectionService->getPreparedProductsCollection($products);
    }
}
