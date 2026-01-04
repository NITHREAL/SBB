<?php

namespace Domain\Product\Services\Basket;

use Domain\Basket\Services\BasketService;
use Illuminate\Support\Collection;

class ProductBasketPropertiesService
{
    private Collection $basketProducts;

    public function __construct(
        private BasketService $basketService,
    ) {
        $this->basketService->actualizeProducts();
        $this->setBasketProducts();
    }

    public function setBasketProperties(Collection $products): Collection
    {
        return $products->map(function ($product) {
            return $this->setBasketPropertiesForOne($product);
        });
    }

    public function setBasketPropertiesForOne(object $product): object
    {
        $productInCart = $this->getProductInCart($product);

        $count = !empty($productInCart)
            ? max((int) $productInCart->count, 0)
            : 0;
        $weight = !empty($productInCart)
            ? max((float) $productInCart->basketWeight, 0)
            : 0;

        $product->setAttribute('count_in_basket', $count);
        $product->setAttribute('weight_in_basket', $weight);

        $sum = $product->is_weight
            ? $product->price * $weight
            : $product->price * $count;

        $product->setAttribute('sum', $sum);

        return $product;
    }

    private function getProductInCart(object $product): ?object
    {
        return $this->basketProducts->where('id', $product->id)->first();
    }

    private function setBasketProducts(): void
    {
        $this->basketProducts = $this->basketService->getBasketInstance()->products;
    }
}
