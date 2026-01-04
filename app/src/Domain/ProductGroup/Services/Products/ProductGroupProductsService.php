<?php

namespace Domain\ProductGroup\Services\Products;

use Domain\ProductGroup\Models\ProductGroup;
use Illuminate\Support\Arr;

class ProductGroupProductsService
{
    private int $defaultGroupProductSortValue = 500;

    public function updateProductGroupProductsRelation(ProductGroup $group, array $products): void
    {
        $productsData = [];

        foreach ($products as $product) {
            $id = Arr::get($product, 'id');
            $sort = Arr::get($product, 'pivot.sort') ?? $this->defaultGroupProductSortValue;

            $productsData[$id] = [
                'product_id'    => $id,
                'sort'          => $sort,
            ];
        }

        $group->products()->sync($productsData);
    }
}
