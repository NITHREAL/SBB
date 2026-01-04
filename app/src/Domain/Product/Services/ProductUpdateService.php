<?php

namespace Domain\Product\Services;

use Domain\Product\DTO\Product\ProductDTO;
use Domain\Product\Models\Product;
use Illuminate\Support\Arr;

class ProductUpdateService
{
    private int $defaultSort = 500;

    public function updateProductData(Product $product, ProductDTO $productDTO): Product
    {
        $product = $this->getFilledProduct($product, $productDTO);

        $this->updateRelatedProducts($product, $productDTO->getRelatedProducts());

        $product->save();

        return $product;
    }

    private function getFilledProduct(Product $product, ProductDTO $productDTO): Product
    {
        return $product->fill([
            'slug'              => $productDTO->getSlug(),
            'sort'              => $productDTO->getSort(),
            'show_as_preorder'  => $productDTO->isShowAsPreorder(),
            'by_points'         => $productDTO->isByPoints(),
            'vegan'             => $productDTO->isVegan(),
        ]);
    }

    private function updateRelatedProducts(Product $product, array $relatedProducts): void
    {
        $relatedProductsData = [];

        foreach ($relatedProducts as $relatedProduct) {
            $id = Arr::get($relatedProduct, 'id');
            $sort = Arr::get($relatedProduct, 'pivot.sort') ?? $this->defaultSort;

            $relatedProductsData[$id] = [
                'related_product_id'    => $id,
                'sort'                  => $sort,
            ];
        }

        $product->relatedProducts()->sync($relatedProductsData);
    }
}
