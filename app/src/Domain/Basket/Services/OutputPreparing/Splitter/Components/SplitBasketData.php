<?php

namespace Domain\Basket\Services\OutputPreparing\Splitter\Components;

use Domain\Basket\Helpers\BasketProductHelper;
use Domain\Basket\Models\Basket;
use Domain\Basket\Services\Promocode\BasketPromocodeApplyService;
use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Services\ImageSelection;
use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Models\Delivery\Polygon;
use Domain\Order\Services\Delivery\Polygon\PolygonService;
use Domain\Promocode\Models\Promocode;
use Domain\CouponCategory\Models\CouponCategory;
use Domain\Store\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryCoordinates;
use Infrastructure\Services\Buyer\Facades\BuyerDeliverySubType;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;
use Infrastructure\Services\Buyer\Facades\BuyerStore;
use Infrastructure\Setting\Services\SettingService;

readonly class SplitBasketData
{
    private BasketPromocodeApplyService $promocodeApplyService;

    private PolygonService $polygonService;
    private SettingService $settingService;

    private ?Promocode $promocode;

    private ?CouponCategory $coupon;

    private ?Store $store;

    private ?string $deliveryType;

    private ?string $deliverySubType;

    private array $deliveryCoordinates;

    public function __construct(
        private Basket $basket,
    ) {
        $this->deliveryType = BuyerDeliveryType::getValue();
        $this->deliverySubType = BuyerDeliverySubType::getValue();
        $this->deliveryCoordinates = BuyerDeliveryCoordinates::getValue();
        $this->store = BuyerStore::getSelectedStore();

        $this->promocodeApplyService = app()->make(BasketPromocodeApplyService::class);
        $this->polygonService = app()->make(PolygonService::class);
        $this->settingService = app()->make(SettingService::class);

        $this->promocode = $this->basket->promocode;
        $this->coupon = $this->basket->coupon;
    }

    public function getPreparedBasket(
        Collection $availableProducts,
        Collection $unavailableProducts,
        array $basketParams,
    ): array {
        $availableProducts = $this->getPreparedProductsCollection($availableProducts);

        $productsTotal = $availableProducts->sum('sum');
        $weightTotal = round($availableProducts->sum('weight_total'), 3);
        $productsTotalPrev = $availableProducts->sum('sum_prev');
        $deliveryType = Arr::get($basketParams, 'deliveryType');

        $store1cId = Arr::get($basketParams, 'store1cId')
            ?? Arr::get($basketParams, 'store1cId')
            ?? $this->store->getAttribute('system_id');
        $storeId = Arr::get($basketParams, 'storeId') ?? $this->store->getAttribute('id');
        $cityId = Arr::get($basketParams, 'cityId') ?? $this->store->getAttribute('city_id');

        $deliveryData = $this->getDeliveryData($productsTotalPrev, $deliveryType, $storeId);

        $deliveryPrice = Arr::get($deliveryData, 'price');
        $total = $this->getBasketTotal($productsTotal, $deliveryPrice);
        $totalWithoutDiscount = $this->getBasketTotal($productsTotalPrev, $deliveryPrice);
        $discount = $this->getBasketDiscount($productsTotal, $productsTotalPrev);

        $timeInterval = Arr::get($basketParams, 'deliveryIntervalTime');


        return [
            'date'                      => Arr::get($basketParams, 'deliveryIntervalDate'),
            'time'                      => $timeInterval,
            'delivery_type'             => $deliveryType,
            'delivery_sub_type'         => Arr::get($basketParams, 'deliverySubType'),
            'delivery_price'            => $deliveryPrice,
            'for_free_delivery'         => 0,
            'address'                   => Arr::get($basketParams, 'address'),
            'store_system_id'           => $store1cId,
            'store_id'                  => $storeId,
            'store_name'                 => $this->store->title,
            'city_id'                   => $cityId,
            'total'                     => $total,
            'total_without_discount'    => $totalWithoutDiscount,
            'products_total'            => $productsTotal,
            'weight_total'              => $weightTotal,
            'products_total_prev'       => $productsTotalPrev,
            'discount'                  => $discount,
            'products'                  => $availableProducts,
            'unavailable_products'      => $unavailableProducts,
            'is_available'              => Arr::get($deliveryData, 'isAvailable'),
            'available_from'            => Arr::get($deliveryData, 'availableFrom'),
            'time_label'                => !empty($timeInterval)
                ? OrderDeliveryHelper::getPreparedDeliveryTimeLabel($timeInterval)
                : null,
        ];
    }

    private function getPreparedProductsCollection(Collection $products): Collection
    {
        if ($this->promocode && $this->promocode->percentage) {
            $products = $this->promocodeApplyService->applyPromocodeToProducts(
                $this->promocode,
                $products,
            );
        }

        $images = ImageSelection::getProductsImages(
            $products->pluck('id')->toArray()
        );

        return $products->map(function ($item) use ($images) {
            $item->sum = BasketProductHelper::calculateProductSum($item, true);
            $item->sum_prev = BasketProductHelper::calculateProductSum($item);

            if ($item->is_weight) {
                $item->weight_total = $item->basketWeight;
            } else {
                $item->weight_total = $item->weight * $item->count;
            }

            $image = $images->where('owner_id', $item->id)->first();

            if ($image) {
                $item = ImagePropertiesHelper::setImageProperties($item, $image);
            }

            return $item;
        });
    }

    private function getBasketTotal(float $productsTotal, float $deliveryPrice = null): float
    {
        // Если стоимость доставки не передана, то она не учитывается
        $total = $deliveryPrice
            ? round($productsTotal + $deliveryPrice, 2)
            : $productsTotal;

        if ($this->promocode && !$this->promocode->percentage) {
            $total = $this->promocodeApplyService->applyPromocodeToBasketTotal($this->promocode, $total);
        }

        return $total;
    }

    private function getBasketDiscount(
        float $productsTotal,
        float $productsTotalPrev,
    ): float {
        $discount = 0;

        if ($this->promocode && $this->promocode->percentage) {
            // Если применен промокод, у которого скидка процентная, то скидка применяется к каждому товару
            $discount = max($productsTotalPrev - $productsTotal, 0);
        } elseif ($this->coupon || ($this->promocode && !$this->promocode->percentage)) {
            // Если применен купон или промокод с фиксированной суммой, то скидка фиксированная и применяется к сумме заказа
            $discount = max($productsTotal - $this->getBasketTotal($productsTotal), 0);
        }

        return round($discount, 2);
    }

    private function getDeliveryData(
        float $totalPrev,
        string $deliveryType,
        int $storeId,
    ): array {
        $isDelivery = $deliveryType === DeliveryTypeEnum::delivery()->value;
        $polygon = $this->deliveryCoordinates
            ? $this->getDeliveryPolygon($storeId)
            : null;

        if ($isDelivery && $polygon) {
            $deliveryData = $this->polygonService->getDeliveryDataBySum($polygon, $totalPrev);

            $price = Arr::get($deliveryData, 'price');
            $isAvailable = Arr::get($deliveryData, 'isAvailable');
            $availableFrom = Arr::get($deliveryData, 'availableFrom');
        } else {
            $price = 0;
            $isAvailable = true;
            $availableFrom = 0;
        }

        return [
            'store1cId'         => $polygon?->getAttribute('store_system_id'),
            'price'             => round($price, 2),
            'isAvailable'       => $isAvailable,
            'availableFrom'     => round($availableFrom, 2),
        ];
    }

    private function getDeliveryPolygon(int $storeId): ?Polygon
    {
        $store = Store::findOrFail($storeId);

        return $this->polygonService->findPolygonByCoordinates(
            $store,
            Arr::get($this->deliveryCoordinates, 'latitude'),
            Arr::get($this->deliveryCoordinates, 'longitude'),
            $this->deliverySubType,
        );
    }
}
