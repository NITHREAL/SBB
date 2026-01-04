<?php

namespace Domain\Product\Services;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Services\ImageSelection;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Product\Helpers\ProductHelper;
use Domain\Product\Helpers\ProductWeightHelper;
use Domain\Product\Models\Product;
use Domain\Product\Services\Basket\ProductBasketPropertiesService;
use Domain\Product\Services\Category\CategorySelection;
use Domain\Product\Services\Favorite\ProductFavoritedPropertyService;
use Domain\Product\Services\Leftover\ProductLeftoverService;
use Domain\Product\Services\Tag\ProductTagService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;

class ProductCollectionService
{
    public function __construct(
        private readonly ProductLeftoverService          $leftoverService,
        private readonly ProductFavoritedPropertyService $favoritedPropertyService,
        private readonly ProductBasketPropertiesService  $basketPropertiesService,
        private readonly ProductTagService               $productTagService,
    ) {
    }

    public function getPreparedPaginatedProductsCollection(LengthAwarePaginator $products): LengthAwarePaginator
    {
        return $products->setCollection(
            $this->getPreparedProductsCollection($products->getCollection())
        );
    }

    public function getPreparedProductsCollection(Collection $products): Collection
    {
        $collection = $this->leftoverService->setLeftoverProperties($products);
        $collection = $this->favoritedPropertyService->defineFavoritedPropertyOnCollection($collection);
        $collection = $this->basketPropertiesService->setBasketProperties($collection);
        $collection = $this->productTagService->setTagsToProductsCollection($collection);

        return $this->prepareProductsCollectionData($collection);
    }

    public function getPreparedProduct(Product $product): object
    {
        $product = $this->leftoverService->setLeftoverPropertiesForOne($product);
        $product = $this->favoritedPropertyService->defineFavoritedPropertyOnObject($product);
        $product = $this->basketPropertiesService->setBasketPropertiesForOne($product);
        $product = $this->productTagService->setTagsToOneProduct($product);

        $deliverySubType = BuyerDeliverySubType::getValue();
        $images = ImageSelection::getProductsImages([$product->id]);
        $specialCategories = CategorySelection::getSpecialCategoriesByProducts([$product->id1C]);

        return $this->prepareProductData($product, $deliverySubType, $images, $specialCategories);
    }

    private function prepareProductsCollectionData(Collection $products): Collection
    {
        $deliverySubType = BuyerDeliverySubType::getValue();
        $images = ImageSelection::getProductsImages(
            $products->pluck('id')->toArray()
        );
        $specialCategories = CategorySelection::getSpecialCategoriesByProducts(
            $products->pluck('id1C')->toArray()
        );

        return $products
            ->map(function ($product) use ($deliverySubType, $images, $specialCategories) {
                return $this->prepareProductData($product, $deliverySubType, $images, $specialCategories);
            });
    }

    private function prepareProductData(
        object $product,
        string $deliverySubType,
        Collection $images,
        Collection $specialCategories,
    ): object {
        if ($product->by_preorder && $product->available_count) {
            $product->by_preorder = false;
        }

        $availableToBuy = $this->getProductAvailableToBuyAttribute(
            $product,
            $deliverySubType,
            $specialCategories->where('product1CId', $product->id1C)->count(),
        );

        $unitData = ProductHelper::getProductUnitData($product);

        $product->can_buy = $availableToBuy;
        $product->unitTitle = Arr::get($unitData, 'title');
        $product->price_unit = sprintf(
            '%s %s',
            Arr::get($unitData, 'rate'),
            Arr::get($unitData, 'title'),
        );

        $weightData = ProductWeightHelper::getWeightData($product);

        $product->weight = Arr::get($weightData, 'value');
        $product->weight_unit = Arr::get($weightData, 'title');

        return $this->setImage($product, $images);
    }

    private function getProductAvailableToBuyAttribute(
        object $item,
        string $deliverySubType,
        bool $hasSpecialCategoryType,
    ): bool {
        $price = Arr::get($item->prices, 'display.price');

        return $price
            && (
                $hasSpecialCategoryType
                || ($deliverySubType === 'other' && !$item->by_preorder && (bool)$item->in_stock===true)
                || ($item->by_preorder  || $item->cooking)
                || ($item->cooking && in_array($deliverySubType, [
                        PolygonDeliveryTypeEnum::extended()->value,
                        PickupTypeEnum::today()->value
                    ], true))
                || ($item->available_count && !$item->cooking && !$item->by_preorder && $deliverySubType !== 'other')
            );
    }

    private function setImage(object $product, Collection $images): object
    {
        $images = $images->where('owner_id', $product->id);

        if ($images->count()) {
            $product = ImagePropertiesHelper::setImageProperties($product, $images->first());
            $product = ImagePropertiesHelper::setPreparedImagesProperty($product, $images);
        }

        return $product;
    }
}
