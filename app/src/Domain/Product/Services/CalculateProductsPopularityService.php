<?php

namespace Domain\Product\Services;

use Domain\Product\Helpers\ProductPopularHelper;
use Domain\Product\Jobs\UpdateProductPopularityJob;
use Domain\Product\Models\Product;

class CalculateProductsPopularityService
{
    private const LIMIT = 500;

    public function calculateProductsPopularity(): void
    {
        $timeForCalculating = ProductPopularHelper::getIntervalTimeForCalculating();

        $products = Product::query()
            ->whereOrderedInTime(
                $timeForCalculating['from'],
                $timeForCalculating['to']
            )->get();

        $dataForUpdate = [];

        $chunks = $products->chunk(self::LIMIT);

        foreach ($chunks as $chunk) {
            $dataForUpdate = $chunk->map(function ($product) {
                return [
                    'id' => $product['id'],
                    'popular' => $product['total_count'],
                    'system_id' => $product['system_id'],
                    'sku' => $product['sku'],
                    'slug' => $product['slug'],
                    'farmer_system_id' => $product['farmer_system_id'],
                    'unit_system_id' => $product['unit_system_id'],
                    'title' => $product['title'],
                ];
            })->toArray();

            if (count($dataForUpdate) >= self::LIMIT) {
                UpdateProductPopularityJob::dispatch($dataForUpdate);
                $dataForUpdate = [];
            }
        }

        if (!empty($dataForUpdate)) {
            UpdateProductPopularityJob::dispatch($dataForUpdate);
        }
    }
}
