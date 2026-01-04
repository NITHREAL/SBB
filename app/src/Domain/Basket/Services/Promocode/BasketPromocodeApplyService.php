<?php

namespace Domain\Basket\Services\Promocode;

use Domain\Promocode\Models\Promocode;
use Illuminate\Support\Collection;

class BasketPromocodeApplyService
{
    public function applyPromocodeToBasketTotal(Promocode $promocode, float $basketTotal): float
    {
        return max(
            $basketTotal - $promocode->discount,
            0,
        );
    }

    public function applyPromocodeToProducts(Promocode $promocode, Collection $products): Collection
    {
        // Логика применения промокодов, у которых процентная скидка. Она применяется к каждому товару
        return $products->map(function ($product) use ($promocode) {
            $pricePromo = $product->price * ((100 - $promocode->discount) / 100);

            $product->price_discount = $pricePromo;
            $product->sum = $pricePromo * $product->count;

            return $product;
        });
    }
}
