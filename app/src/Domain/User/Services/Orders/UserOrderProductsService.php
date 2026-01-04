<?php

namespace Domain\User\Services\Orders;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Services\ImageSelection;
use Domain\Product\Helpers\ProductHelper;
use Domain\Product\Models\Product;
use Domain\Product\Services\Favorite\ProductFavoritedPropertyService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

readonly class UserOrderProductsService
{
    public function __construct(
        private ProductFavoritedPropertyService $favoritedPropertyService,
    ) {
    }

    public function getProducts(array $orderIds): Collection
    {
        $products = Product::query()->ordersQuery($orderIds)->get();

        return $this->prepareProducts($products);
    }

    public function prepareProducts(Collection $products): Collection
    {
        $products = $this->favoritedPropertyService->defineFavoritedPropertyOnCollection($products);

        $images = ImageSelection::getProductsImages(
            $products->pluck('id')->toArray()
        );

        return $products->map(function ($product) use ($images) {
            $unitData = ProductHelper::getProductUnitData($product);

            $product->unitTitle = Arr::get($unitData, 'title');
            $product->price_unit = sprintf(
                '%s %s',
                Arr::get($unitData, 'rate'),
                Arr::get($unitData, 'title'),
            );
            $product->price = round($product->price, 2);

            $priceDiscount = $product->priceDiscount ?? $product->pricePromo;
            $product->priceDiscount = $priceDiscount ? round($priceDiscount, 2) : null;

            $product->setAttribute('sum_unit', ProductHelper::getProductSumUnit($product, $product->count));

            $image = $images->where('owner_id', $product->id)->first();

            if ($image) {
                $product = ImagePropertiesHelper::setImageProperties($product, $image);
            }

            return $product;
        });
    }
}
