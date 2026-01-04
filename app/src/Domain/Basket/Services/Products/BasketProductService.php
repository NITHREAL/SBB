<?php

namespace Domain\Basket\Services\Products;

use Domain\Basket\Helpers\BasketProductHelper;
use Domain\Basket\Models\Basket;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Product\Helpers\DeliveryDateHelper;
use Domain\Product\Helpers\ProductHelper;
use Domain\Product\Models\Product;
use Domain\Product\Services\Category\CategorySelection;
use Domain\Product\Services\Delivery\DeliveryDatesService;
use Domain\Product\Services\Favorite\ProductFavoritedPropertyService;
use Domain\Product\Services\Leftover\ProductLeftoverService;
use Domain\Store\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;

class BasketProductService
{
    private string $deliverySubType;

    private Collection $products;

    private Collection $availableProducts;

    private Collection $unavailableProducts;

    public function __construct(
        private readonly ProductLeftoverService $leftoverService,
        private readonly ProductFavoritedPropertyService $favoritedPropertyService,
        private readonly DeliveryDatesService   $deliveryDatesService,
    ) {
        $this->deliverySubType = BuyerDeliverySubType::getValue();
        $this->products = collect();
        $this->availableProducts = collect();
        $this->unavailableProducts = collect();
    }

    public function getRecalculatedProducts(Basket $basket, Store $store): array
    {
        $this->resetProductCollections();

        $products = $this->leftoverService->setLeftoverProperties(
            $this->getProducts($basket->getAttribute('id'), $store->getAttribute('system_id'))
        );

        $this->products = $this->favoritedPropertyService->defineFavoritedPropertyOnCollection($products);

        $this->resolveProductsAvailability();

        return [
            'products'              => $this->products,
            'availableProducts'     => $this->availableProducts,
            'unavailableProducts'   => $this->unavailableProducts,
        ];
    }

    public function getProducts(int $basketId, string $store1cId): Collection
    {
        return Product::query()
            ->basketQuery($store1cId, $basketId)
            ->get();
    }

    private function resolveProductsAvailability(): void
    {
        $specialCategories = CategorySelection::getSpecialCategoriesByProducts(
            $this->products->pluck('id1C')->toArray(),
        );

        $deliveryDates = $this->deliveryDatesService->getNearestDeliveryDatesByCollection(
            $this->products->pluck('id')->toArray(),
        );

        $this->products->map(function ($product) use ($specialCategories, $deliveryDates) {
            // Получаем ближайшую дату доставки/получения для товара
            $product->nearest_date = $this->getNearestDeliveryDate($deliveryDates, $product);

            if ($this->hasSpecialCategory($specialCategories, $product)) {
                // Если товар относится к специальной категории
                $this->addToAvailableProducts($product);
            } else {
                $this->handleAvailability($product);
            }
        });
    }

    private function handleAvailability(Product $product): void
    {
        $overCount = $product->count - $product->available_count;

        if ($overCount > 0 && !$product->cooking && !$product->by_preorder) {
            $this->addToUnavailableProducts($product, $overCount);

            if ($product->available_count > 0) {
                $this->addToAvailableProducts($product, $product->available_count);
            }
        } elseif (!$product->by_preorder && !$product->available) {
            $this->addToUnavailableProducts($product);
        } else {
            $this->addToAvailableProducts($product);
        }
    }

    private function handleAvailableToday(Product $product): void
    {
        $overCount = $product->count - $product->available_count;

        if ($overCount > 0 && !$product->cooking && !$product->by_preorder) {
            $this->addToUnavailableProducts($product, $overCount);

            $this->addToAvailableProducts($product, $product->available_count);
        } else {
            if ($product->by_preorder && empty($product->nearest_date)) {
                $this->addToUnavailableProducts($product);
            } else {
                $this->addToAvailableProducts($product);
            }
        }
    }

    private function handleAvailableOtherDay(Product $product): void
    {
        if (!$product->available && !$product->by_preorder) {
            $this->addToUnavailableProducts($product);
        } else {
            $this->addToAvailableProducts($product);
        }
    }

    private function addToAvailableProducts(Product $product, int $count = null): void
    {
        $count = $count ?? $product->count;

        $this->availableProducts = $this->addToProductsCollection(
            $this->availableProducts,
            $product,
            $count,
        );
    }

    private function addToUnavailableProducts(Product $product, int $count = null): void
    {
        $count = $count ?? $product->count;

        $this->unavailableProducts = $this->addToProductsCollection(
            $this->unavailableProducts,
            $product,
            $count,
        );
    }

    private function addToProductsCollection(
        Collection $productsCollection,
        Product $product,
        int $count,
    ): Collection {
        $productPrices = Arr::get($product->prices, 'display');

        $productForAdd = clone($product);
        $productForAdd->setAttribute('count', $count);
        $productForAdd->setAttribute('unitTitle', ProductHelper::getProductUnitTitle($product));
        $productForAdd->setAttribute('price', Arr::get($productPrices, 'price', 0));
        $productForAdd->setAttribute('price_discount', Arr::get($productPrices, 'price_discount'));
        $productForAdd->setAttribute('price_unit', ProductHelper::getProductPriceUnit($product));
        $productForAdd->setAttribute('sum', BasketProductHelper::calculateProductSum($product));
        $productForAdd->setAttribute('sum_unit', ProductHelper::getProductSumUnit($product, $count));

        $existedProduct = $productsCollection
            ->where('id', $productForAdd->id)
            ->first();

        if ($existedProduct) {
            $productsCollection->map(function ($item) use ($existedProduct, $productForAdd) {
                if ($item->id === $existedProduct->id) {
                    $item->setAttribute('count', $existedProduct->count + $productForAdd->count);
                }

                return $item;
            });
        } else {
            $productsCollection->push($productForAdd);
        }

        return $productsCollection;
    }

    private function hasSpecialCategory(Collection $specialCategories, Product $product): bool
    {
        return $specialCategories->where('product1CId', $product->getAttribute('system_id'))->isNotEmpty();
    }

    private function getNearestDeliveryDate(Collection $deliveryDates, Product $product): ?string
    {
        $dates = [];

        $deliveryDates = $deliveryDates
            ->where('product_id', $product->id)
            ->pluck('date')
            ->toArray();

        if (count($deliveryDates)) {
            $dates = DeliveryDateHelper::getNearestDateDelivery($deliveryDates);
        }

        return Arr::first($dates);
    }

    private function isNeedToday(): bool
    {
        return in_array(
            $this->deliverySubType,
            [
                PolygonDeliveryTypeEnum::fast()->value,
                PolygonDeliveryTypeEnum::extended()->value,
                PickupTypeEnum::today()->value
            ],
            true,
        );
    }

    private function resetProductCollections(): void
    {
        $this->products = collect();
        $this->availableProducts = collect();
        $this->unavailableProducts = collect();
    }

    private function filterAvailableProducts(): void
    {
        $this->availableProducts->filter(function ($product) {
            return ($product->fromOrder || $product->available || $product->by_preorder)
                || ($product->count > 0 || $product->by_preorder);
        });
    }
}
