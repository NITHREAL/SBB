<?php

namespace App\Orchid\Layouts\Shop\ExpectedProducts;

use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ExpectedProductListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'expected_products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('product.title', __('admin.expected_product.table.product')),
            TD::make('user.first_name', __('admin.expected_product.table.user')),
            TD::make('created_at', __('admin.expected_product.table.created_at'))->render(fn($item) => $item->created_at->format('d-m-Y H:i'))
        ];
    }
}
