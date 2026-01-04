<?php

namespace Domain\Product\Helpers;

use Domain\Product\Enums\ProductRateTypeEnum;
use Illuminate\Support\Arr;

class ProductHelper
{
    private const DEFAULT_WEIGHT_PRODUCT_UNIT_RATE = 100;

    private  const DEFAULT_PRODUCT_UNIT_RATE = 1;

    private const GRAMS_PER_KG = 1000;

    public static function getProductUnitData(object $item): array
    {
        $title = $item->unitTitle ?? ProductRateTypeEnum::pcs()->label;
        $rate = self::DEFAULT_PRODUCT_UNIT_RATE;

        if (ProductWeightHelper::isWeightProduct($item)) {
            $title = ProductRateTypeEnum::kg()->label;
        }

        return compact('title', 'rate');
    }

    public static function getProductPriceUnit(object $product): string
    {
        $unitData = self::getProductUnitData($product);

        return sprintf(
            '%s %s',
            Arr::get($unitData, 'rate'),
            Arr::get($unitData, 'title'),
        );
    }

    public static function getProductUnitTitle(object $product): string
    {
        $unitData = self::getProductUnitData($product);

        return Arr::get($unitData, 'title');
    }

    public static function getProductSumUnit(object $product, int $count): string
    {
        $unitData = self::getProductUnitData($product);

        $count = max($count, 1);

        if ($product->is_weight) {
            $count *= $product->weight;
        } else {
            $count = empty($count) ? self::DEFAULT_PRODUCT_UNIT_RATE : $count;
        }

        return sprintf('%s %s', round($count, 3), Arr::get($unitData, 'title'));
    }

    public static function getPreparedProductPrice(?float $price): ?int
    {
        return is_null($price) ? null : (int) round($price);
    }
}
