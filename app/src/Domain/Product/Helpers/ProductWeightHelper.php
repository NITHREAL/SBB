<?php

namespace Domain\Product\Helpers;

use Domain\Product\Enums\ProductRateTypeEnum;

class ProductWeightHelper
{
    private const WEIGHT_BY_GRAM_LIMIT = 0.6;

    public static function isWeightProduct(object $product): bool
    {
        return (bool) $product->is_weight;
    }

    public static function getWeightData(object $product): array
    {
        $result = [];

        $weight = $product->weight;

        if ($weight) {
            $weightInKg = $weight / 1000;

            if ($weightInKg >= 1) {
                $weight = $weightInKg;
                $weightTitle = ProductRateTypeEnum::kg()->label;
            } else {
                $weightTitle = ProductRateTypeEnum::g()->label;
            }

            $result = [
                'value' => round($weight, 3),
                'title' => $weightTitle,
            ];
        }

        return $result;
    }

    public static function isWeightByGram(object $product, float $weight = null): bool
    {
        $weight = $weight ?? (float) $product->weight;

        return $weight && $product->weight < self::WEIGHT_BY_GRAM_LIMIT;
    }
}
