<?php

namespace Domain\Basket\Services\Products;

use Domain\Product\Services\ProductCollectionService;
use Domain\Product\Services\RecommendedProduct\RecommendedProductSelectionService;
use Domain\Product\Services\RelatedProduct\ProductRelatedProductsService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class BasketProposedProducts
{
    public function __construct(
        private readonly ProductRelatedProductsService $productRelatedProductsService,
        private readonly RecommendedProductSelectionService $recommendedProductSelectionService,
        private readonly ProductCollectionService $productCollectionService,
    ) {
    }

    public function getBasketProposedProducts(array $baskets): Collection
    {
        $basketProducts = collect();

        foreach ($baskets as $basket) {
            $basketProducts = $basketProducts->merge(Arr::get($basket, 'products', collect()));
        }

        $store1cId = $this->getStore1cId($baskets);

        if (count($basketProducts)) {
            $proposedProducts = $this->getRelatedProducts($basketProducts, $store1cId);
        } else {
            $proposedProducts = $this->getRecommendedProducts();
        }

        return $proposedProducts;
    }

    private function getRelatedProducts(
        Collection $basketProducts,
        string $store1cId,
    ): Collection {
        $relatedProducts = $this->productRelatedProductsService->getRelatedProducts(
            $basketProducts->pluck('id')->toArray(),
            $store1cId
        );

        return $this->productCollectionService->getPreparedProductsCollection($relatedProducts);
    }

    private function getRecommendedProducts(): Collection
    {
        $recommendedProducts = $this->recommendedProductSelectionService->getRecommendedProducts();

        return $this->productCollectionService->getPreparedProductsCollection($recommendedProducts);
    }

    private function getStore1cId(array $baskets): string
    {
        $store1cId = Arr::get(
            Arr::first($baskets),
            'store_system_id',
        );

        return $store1cId ?? BuyerStore::getOneCId();
    }
}
