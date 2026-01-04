<?php

namespace Domain\Product\Services\ExpectedProduct;

use Domain\Product\DTO\Product\ExpectedProductDTO;
use Domain\Product\Helpers\ExpectedProductsHelper;
use Domain\Product\Models\ExpectedProduct;
use Domain\Product\Models\Product;

class ExpectedProductChangeServices
{
    public function store(ExpectedProductDTO $expectedProductDTO): ExpectedProduct
    {
        $product = Product::findOrFail($expectedProductDTO->getProductId());

        $expectedProduct = new ExpectedProduct();

        $expectedProduct->product()->associate($product);
        $expectedProduct->user()->associate($expectedProductDTO->getUser());

        $expectedProduct->save();

        return $expectedProduct;
    }

    public function deleteOutdatedExpectedProducts(): void
    {
        ExpectedProduct::query()
            ->where('created_at', '<', ExpectedProductsHelper::getExpectedProductOutdatedInterval())
            ->delete();
    }
}
