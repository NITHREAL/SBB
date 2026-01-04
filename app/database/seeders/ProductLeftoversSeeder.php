<?php

namespace Database\Seeders;

use Domain\Product\Jobs\Leftovers\ProductLeftoversGenerateJob;
use Domain\Product\Models\Category;
use Domain\Store\Models\ProductStore;
use Domain\Store\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductLeftoversSeeder extends Seeder
{
    private const PRODUCTS_CHUNK_LIMIT = 10000;

    private const START_TIME_DELAY = 60; // секунд

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Collection $stores */
        $stores = Store::query()->whereActive()->get();
        $categories = Category::query()->whereActive()->get();

        foreach ($categories as $category) {
            $products = $category->products()->whereActive()->limit(100)->get();

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
}
