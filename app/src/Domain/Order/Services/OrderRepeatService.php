<?php

namespace Domain\Order\Services;

use Domain\Basket\Exceptions\BasketProductException;
use Domain\Basket\Services\BasketService;
use Domain\Product\Models\Product;
use Illuminate\Support\Collection;

class OrderRepeatService
{
    public function __construct(
        private readonly BasketService $basketService,
    ) {
    }

    /**
     * @throws BasketProductException
     */
    public function repeatOrder(int $orderId): array
    {
        $products = $this->getProducts($orderId);

        foreach ($products as $product) {
            $basket = $this->basketService->addProduct($product->id, true, $product->count);
        }

        return $basket ?? $this->basketService->getBasket();
    }

    private function getProducts(int $orderId): Collection
    {
        return Product::query()->ordersQuery([$orderId])->get();
    }
}
