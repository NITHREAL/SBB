<?php

namespace Domain\Promocode\Models\Accessors;

use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Domain\Promocode\Models\Promocode;

final class TotalDiscount
{
    public function __construct(
        private readonly Promocode $promocode,
    ) {
    }

    public function __invoke()
    {
        $totalDiscount = 0;

        if ($this->promocode->exists) {
            $orders = Order::query()
                ->select('id', 'discount')
                ->where('promo_id', $this->promocode->id)
                ->whereNotCanceled()
                ->with('products')
                ->get();

            foreach ($orders as $order) {
                $totalDiscount += $order->discount;
                $totalDiscount += $order->products->sum(function (Product $product) {
                    $discount = 0;

                    if ($product->pivot->price_promo) {
                        $discount = $product->pivot->price - $product->pivot->price_promo;
                    }

                    return $discount;
                });
            }
        }

        return $totalDiscount;
    }
}
