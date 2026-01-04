<?php

namespace Domain\Basket\Services\AbandonedBaskets;

use Domain\Basket\Models\Basket;
use Domain\Product\Models\Product;
use Domain\Product\Services\Leftover\ProductLeftoverService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AbandonedBasketService
{
    public function __construct(
        private readonly ProductLeftoverService $leftoverService,
    ) {
    }

    public function getCollection($limit = 15): Collection
    {
        $hours  = request()->get('hours', 0);
        $sumMin = request()->get('summ_min', null);
        $sumMax = request()->get('summ_max', null);
        $available = request()->get('available', false);

        $baskets = Basket::query()
            ->where('updated_at', '<', Carbon::now()->subHours($hours))
            ->whereNotNull('user_id')
            ->whereHas('products')
            ->with('products', 'user.store')
            ->filters()
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        return $baskets
            ->map(function (Basket $basket) {
                $basket->products = $this->leftoverService->setLeftoverProperties($basket->products);

                return $basket;
            })
            ->filter(function (Basket $basket) use ($sumMin, $sumMax, $available) {
                $sum = $basket
                    ->products
                    ->sum(function (Product $product) {
                        $price = $product->prices['real']['price_discount'] ?? $product->prices['real']['price'];

                        return $price * $product->pivot->count;
                    });

                if ($sumMin && $sum < $sumMin) {
                    return false;
                }

                if ($sumMax && $sum > $sumMax) {
                    return false;
                }

                if ($available && $basket->products->where('in_stock', true)->count()) {
                    return false;
                }

                return true;
            });
    }
}
