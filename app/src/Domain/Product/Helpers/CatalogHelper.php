<?php

namespace Domain\Product\Helpers;

class CatalogHelper
{
    public const SORT_COLUMN_VALUES = [
        'default',
        'title',
        'price',
        'popular',
        'rating',
        'discount',
    ];

    public const FILTER_VALUES = [
        'available_today',
        'for_vegan',
        'farmers',
    ];

    public static function getSortByColumnValue(string $paramValue): string
    {
        return match($paramValue) {
            'default'   => 'products.sort',
            'title'     => 'products.title',
            'price'     => 'leftovers.price',
            'popular'   => 'products.popular',
            'rating'    => 'products.rating',
            'discount'  => 'leftovers.price_discount',
        };
    }
}
