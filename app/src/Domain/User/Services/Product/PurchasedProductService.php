<?php

namespace Domain\User\Services\Product;

use Domain\Product\Models\Product;
use Domain\Product\Services\ProductCollectionService;
use Domain\User\DTO\Product\PurchasedProductDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

readonly class PurchasedProductService
{
    public function __construct(
        private ProductCollectionService  $productCollectionService,
    ) {
    }

    public function purchasedProducts(
        PurchasedProductDTO $previouslyPurchasedProductDTO
    ): LengthAwarePaginator {
        $storeOneCId = BuyerStore::getOneCId();

        $products =  Product::query()
            ->userProductsQuery($previouslyPurchasedProductDTO->getUser()->id, $storeOneCId)
            ->paginate($previouslyPurchasedProductDTO->getLimit());

        return $this->productCollectionService->getPreparedPaginatedProductsCollection($products);
    }
}
