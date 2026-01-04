<?php

namespace Domain\Product\Services\WeekProduct;

use Domain\Product\Models\WeekProduct;
use Illuminate\Support\Arr;

class WeekProductService
{
    private int $defaultProductSort = 500;

    public function updateWeekProducts(array $data): void
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

        $this->clearWeekProducts();

        WeekProduct::insert($productsData);
    }

    public function clearWeekProducts(): void
    {
        WeekProduct::truncate();
    }
}
