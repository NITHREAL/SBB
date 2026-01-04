<?php

namespace Domain\Order\Services\Sbermarket;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Illuminate\Support\Arr;

class SbermarketOrderProductsService
{
    private const DEFAULT_WEIGHT_STACK = 1000;

    public function getPreparedOrderProducts(Order $order): array
    {
        $result = [];

        foreach ($order->products as $key => $product) {
            $result[$key] = [
                'id'                => $product->id,
                'originalQuantity'  => $product->pivot->original_quantity ?? 0,
                'quantity'          => $product->weight > 0
                    ? $product->pivot->count / $product->weight
                    : (int) $product->pivot->count,
                'weight'            => $product->weight > 0
                    ? strval($product->pivot->count * self::DEFAULT_WEIGHT_STACK)
                    : "",
            ];
        }

        return $result;
    }

    public function getPreparedProductDataForOrderCreating(Product $product, array $orderProduct): array
    {
        $orderProductWeight = Arr::get($orderProduct, 'weight');
        $orderProductQuantity = Arr::get($orderProduct, 'quantity');
        $orderProductPrice = Arr::get($orderProduct, 'price');

        if ($orderProductWeight) {
            $weight = $orderProductWeight / self::DEFAULT_WEIGHT_STACK;
            $weightByOne = $weight / $orderProductQuantity;

            $price = $orderProductPrice / $weightByOne;
            $priceBuy = $orderProductPrice / $weightByOne;
        } else {
            $price = $orderProductPrice;
            $priceBuy = $orderProductPrice;
        }

        return [
            'unit_system_id'                => $product->getAttribute('unit_system_id'),
            'status'                    => OrderStatusEnum::collecting()->value,
            'price'                     => $price,
            'price_buy'                 => $priceBuy,
            'price_discount'            => Arr::get($orderProduct, 'discountPrice'),
            'weight'                    => $product->getAttribute('weight'),
            'original_quantity'         => Arr::get($orderProduct, 'originalQuantity'),
            'count'                     => $orderProductQuantity,
            'total'                     => Arr::get($orderProduct, 'totalDiscountPrice'),
            'total_without_discount'    => Arr::get($orderProduct, 'totalPrice'),
        ];
    }
}
