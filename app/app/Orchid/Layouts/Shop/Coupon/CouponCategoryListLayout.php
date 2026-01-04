<?php

namespace App\Orchid\Layouts\Shop\Coupon;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CouponCategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            Active::make(),
            TD::make('title', __('admin.title')),
            TD::make('price', __('admin.coupon.price'))->sort(),
            TD::make('created_at', __('admin.created_at'))
                ->render(fn(object $item) => $item->created_at->format('d-m-Y H:i'))
                ->sort(),
            TD::make('updated_at', __('admin.updated_at'))
                ->render(fn(object $item) => $item->updated_at->format('d-m-Y H:i'))
                ->sort(),
            Sort::make(),
            TD::make()->actions([
                new Actions\Edit('platform.coupons.category.edit'),
            ])
        ];
    }
}
