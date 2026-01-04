<?php

namespace Domain\Product\Services\RecommendedProduct;

use Domain\Product\Models\RecommendedProduct;
use Illuminate\Support\Arr;

class RecommendedProductService
{
    private int $defaultProductSort = 500;

    public function updateRecommendedProducts(array $data): void
    {
        $productsData = [];

        foreach ($data as $product) {
            $id = Arr::get($product, 'product_id');
            $sort = Arr::get($product, 'sort') ?? $this->defaultProductSort;

            $productsData[] = [
                'product_id'    => $id,
                'sort'          => $sort,
            ];
        }

        $this->clearRecommendedProducts();

        RecommendedProduct::insert($productsData);
    }

    public function clearRecommendedProducts(): void
    {
        RecommendedProduct::truncate();
    }
}
