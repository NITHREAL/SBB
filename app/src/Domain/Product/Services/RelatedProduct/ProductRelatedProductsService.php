<?php

namespace Domain\Product\Services\RelatedProduct;

use Domain\Product\Models\Product;
use Illuminate\Support\Collection;

class ProductRelatedProductsService
{
    private int $defaultLimit = 8;

    public function getRelatedProducts(
        array $mainProductIds,
        string $store1cId,
        ?int $relatedProductsLimit = null,
    ): Collection {
        $limit = $relatedProductsLimit ?? $this->defaultLimit;

        return Product::query()
            ->smallProductCardsQuery($store1cId)
            ->leftJoin('related_products', 'related_products.related_product_id', '=', 'products.id')
            ->whereIn('related_products.main_product_id', $mainProductIds)
            ->orderBy('related_products.sort')
            ->limit($limit)
            ->get();
    }
}
