<?php

namespace Domain\Product\Services\RecommendedProduct;

use Domain\Product\Models\Product;
use Domain\Product\Models\RecommendedProduct;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class RecommendedProductSelectionService
{
    private int $defaultPopularProductsLimit = 8;

    public function getRecommendedProducts(?int $limit = null): Collection
    {
        $limit = $limit ?? $this->defaultPopularProductsLimit;

        $recommendedProductsData = RecommendedProduct::query()
            ->orderBy('sort')
            ->limit($limit)
            ->get();

        $products = $this->getProducts(
            $recommendedProductsData->pluck('product_id')->toArray(),
        );

        $recommendedProducts = collect();

        foreach ($recommendedProductsData as $recommendedProductData) {

            $product = $products->where('id', $recommendedProductData->product_id)->first();

            if (!empty($product)) {
                $recommendedProducts->push($product);
            }
        }

        return $recommendedProducts;
    }

    private function getProducts(array $productIds): Collection
    {
        $store1cId = BuyerStore::getSelectedStore()->getAttribute('system_id');

        return Product::query()
            ->smallProductCardsQuery($store1cId)
            ->whereIn('products.id', $productIds)
            ->get();
    }
}
