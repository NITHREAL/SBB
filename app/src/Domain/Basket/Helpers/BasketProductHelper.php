<?php

namespace Domain\Basket\Helpers;

use Domain\Product\Models\Product;
use Illuminate\Support\Arr;

class BasketProductHelper
{
    public static function calculateProductSum(Product $product, bool $withDiscount = false): float
    {
        $price = self::getProductBasePrice($product, $withDiscount);

        if ($withDiscount) {
            $price = $product->price_discount > 0 ? $product->price_discount : $price;
        }

        $count = $product->count ?: 1;

        return round($price * $count, 2);
    }

    private static function getProductBasePrice(Product $product, bool $withDiscount): float
    {
        $prices = Arr::get($product->prices, 'real', []);
        $price = Arr::get($prices, 'price', 0);

        if ($withDiscount) {
            $discountPrice = Arr::get($prices, 'price_discount', 0);

            $price = $discountPrice > 0 ? $discountPrice : $price;
        }

        return $price;
    }
}
