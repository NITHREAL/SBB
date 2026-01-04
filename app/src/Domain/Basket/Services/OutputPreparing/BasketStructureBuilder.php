<?php

namespace Domain\Basket\Services\OutputPreparing;

use Domain\Basket\Models\Basket;
use Domain\Basket\Services\OutputPreparing\Splitter\BasketSplitter;
use Domain\Basket\Services\Products\BasketProposedProducts;
use Illuminate\Support\Arr;
use Infrastructure\Services\Buyer\Facades\BuyerToken;

class BasketStructureBuilder
{
    private readonly BasketProposedProducts $basketProposedProductsService;

    public function __construct(
        private readonly Basket $basket,
    ) {
        $this->basketProposedProductsService = app()->make(BasketProposedProducts::class);
    }

    public function getPreparedBasketData(): array
    {
        $baskets = $this->getSplitBasketsData();

        $total = $this->calculateTotal($baskets, true);

        return [
            'token'                         => BuyerToken::getValue(),
            'promocode'                     => $this->basket->promocode,
            'coupon'                        => $this->basket->coupon,
            'total'                         => $total,
            'totalWithoutDiscount'          => $this->calculateTotalWithoutDiscount($baskets, true),
            'productsTotal'                 => $this->calculateTotal($baskets),
            'productsTotalWithoutDiscount'  => $this->calculateTotalWithoutDiscount($baskets),
            'discount'                      => $this->calculateDiscount($baskets),
            'delivery'                      => $this->calculateDelivery($baskets),
//            'bonuses'                       => BasketHelper::calculateBonuses($total),
            'baskets'                       => $baskets,
            'proposedProducts'              => $this->basketProposedProductsService->getBasketProposedProducts($baskets),
            'settings'                      => $this->basket->settings,
        ];
    }

    private function getSplitBasketsData(): array
    {
        // Сервис для получения структуры корзины с разделением в зависимости от даты доставки/получения
        $basketSplitter = new BasketSplitter($this->basket);

        return $basketSplitter->getSplittedByDateBaskets();
    }

    private function calculateTotal(
        array $baskets,
        bool $withDelivery = false,
    ): float {
        $result = 0;

        foreach ($baskets as $basket) {
            $result += $withDelivery
                ? Arr::get($basket, 'total', 0)
                : Arr::get($basket, 'products_total', 0);
        }

        return round($result, 2);
    }

    private function calculateTotalWithoutDiscount(
        array $baskets,
        bool $withDelivery = false,
    ): float {
        $result = 0;

        foreach ($baskets as $basket) {
            $result += $withDelivery
                ? Arr::get($basket, 'total_without_discount', 0)
                : Arr::get($basket, 'products_total_prev', 0);
        }

        return round($result, 2);
    }

    private function calculateDiscount(array $baskets): float
    {
        $result = 0;

        foreach ($baskets as $basket) {
            $result += Arr::get($basket, 'discount', 0);
        }

        return round($result, 2);
    }

    private function calculateDelivery(array $baskets): float
    {
        $result = 0;

        foreach ($baskets as $basket) {
            $result += Arr::get($basket, 'delivery_price', 0);
        }

        return round($result, 2);
    }
}
