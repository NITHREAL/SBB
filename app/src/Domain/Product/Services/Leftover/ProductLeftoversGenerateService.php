<?php

namespace Domain\Product\Services\Leftover;

use Domain\Store\Models\ProductStore;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

readonly class ProductLeftoversGenerateService
{
    public function generateProductsLeftovers(Collection $products, Collection $stores): void
    {
        DB::transaction(function () use ($stores, $products) {
            foreach ($products as $product) {
                foreach ($stores as $store) {
                    $leftoverData = [
                        'hash'              => md5($store->system_id . $product->system_id),
                        'store_system_id'   => $store->system_id,
                        'product_system_id' => $product->system_id,
                        'active'            => true,
                        'price'             => rand(50, 1999),
                        'price_discount'    => 0,
                        'count'             => rand(1, 100),
                        'delivery_schedule' => [],
                    ];

                    ProductStore::firstOrCreate(
                        ['hash' => Arr::get($leftoverData, 'hash')],
                        $leftoverData,
                    );
                }
            }
        });
    }
}
