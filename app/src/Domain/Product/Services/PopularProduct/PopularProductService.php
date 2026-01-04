<?php

namespace Domain\Product\Services\PopularProduct;

use Domain\Product\Models\PopularProduct;
use Illuminate\Support\Arr;

class PopularProductService
{
    private int $defaultProductSort = 500;

    public function updatePopularProducts(array $data): void
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

        $this->clearPopularProducts();

        PopularProduct::insert($productsData);
    }

    public function clearPopularProducts(): void
    {
        PopularProduct::truncate();
    }
}
