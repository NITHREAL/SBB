<?php

namespace Domain\Product\Services\Image;

use Domain\Product\Models\Product;

class ImageAttachService
{
    public array $images;
    public array $productSystemIds;

    public function __construct(array $preparedImagesData)
    {
        $this->images = $preparedImagesData;

        $this->productSystemIds = array_keys($this->images);
    }

    public function attachImagesToProducts(): void
    {
        $products = Product::whereIn('system_id', $this->productSystemIds)->get();

        foreach ($products as $product) {
            $product->images()->delete();

            $product->images()->createMany($this->images[$product->system_id]);
        }
    }
}
