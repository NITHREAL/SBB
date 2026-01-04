<?php

namespace App\Orchid\Filters\Product;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\Product\Models\Product;

class ProductFilter extends RelationFilter
{
    public $parameters = [
        'products'
    ];

    protected ?string $dbColumn = 'product_id';

    protected string $modelClassName = Product::class;

    protected string $modelColumnName = 'title';

    protected bool $multiple = true;

    public function name(): string
    {
        return __('admin.products');
    }
}
