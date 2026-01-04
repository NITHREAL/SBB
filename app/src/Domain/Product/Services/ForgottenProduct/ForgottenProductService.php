<?php

namespace Domain\Product\Services\ForgottenProduct;

use Domain\Product\Models\ForgottenProduct;
use Illuminate\Support\Arr;

class ForgottenProductService
{
    private int $defaultProductSort = 500;

    public function updateForgottenProducts(array $data): void
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

        ForgottenProduct::insert($productsData);
    }

    public function clearPopularProducts(): void
    {
        ForgottenProduct::truncate();
    }
}
