<?php

namespace Domain\Product\Services\Leftover;

use Domain\Product\Helpers\DeliveryDateHelper;
use Domain\Product\Helpers\ProductWeightHelper;
use Domain\Product\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ProductLeftoverService
{
    public function setLeftoverProperties(Collection $products): Collection
    {
        return $products->map(function ($item) {
            return $this->setLeftoverPropertiesForOne($item);
        });
    }

    public function setLeftoverPropertiesForOne(object $item): object
    {
        $item->prices = $this->getProductPrices($item);
        $item->price = Arr::get(
            Arr::get($item->prices, 'real', []),
            'price',
            $item->price
        );
        $item->available_count = $this->getAvailableCount($item);
        $item->available = $item->active;
        $item->in_stock = $item->active && ($item->available_count > 0);
        $item->date_supply = $this->getDeliveryDateSupply(
            json_decode($item->delivery_schedule, true) ?? []
        );

        return $item;
    }

    private function getProductPrices(object $product): array
    {
        $price = (float) $product->price;
        $priceDiscount = $this->getPriceDiscount($product);

        $prices = [
            // Цена в БД, если товар весовой, то цена за 1 кг
            'original' => [
                'price' => $price,
                'price_discount' => $priceDiscount
            ],

            // Цена для frontend части
            'display' => [
                'price' => $price,
                'price_discount' => $priceDiscount
            ],

            // Цена за единицу товара
            'real' => [
                'price' => $price,
                'price_discount' => $priceDiscount
            ],
        ];

        if (ProductWeightHelper::isWeightProduct($product)) {
            $prices['real'] = [
                'price'             => $price * $product->basketWeight,
                'price_discount'    => !empty($priceDiscount) ? $priceDiscount * $product->basketWeight : null,
            ];
        }

        return $prices;
    }

    private function getAvailableCount(object $product): int
    {
        $count = (int) $product->availableCount;

        if ($product->weight > 0) {
            $count = (int) ($product->availableCount / $product->weight);
        }

        return $count;
    }

    private function getDeliveryDateSupply(array $deliveryDates): ?string
    {
        return count($deliveryDates)
            ? DeliveryDateHelper::getNearestDateSupply($deliveryDates)
            : null;
    }

    private function getPriceDiscount(object $product): ?float
    {
        $price = (float)$product->price;
        $priceDiscount = (float) $product->price_discount;

        if (
            ($product->discount_expires_in && Carbon::now()->isAfter($product->discount_expires_in))
            || $price == $priceDiscount
        ) {
            $priceDiscount = null;
        }

        return !empty($priceDiscount) && $priceDiscount > 0
            ? round($priceDiscount, 2)
            : null;
    }
}
