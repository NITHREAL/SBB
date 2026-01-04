<?php

namespace App\Orchid\Filters\Product;

use App\Orchid\Filters\Basic\TextFilter;

class ProductArticleFilter extends TextFilter
{
    public $parameters = [
        'sku'
    ];

    public function name(): string
    {
        return __('admin.product.sku');
    }
}
