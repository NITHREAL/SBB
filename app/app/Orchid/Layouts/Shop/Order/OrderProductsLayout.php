<?php

namespace App\Orchid\Layouts\Shop\Order;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Domain\Unit\Models\Unit;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderProductsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'order.products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        $units = Unit::query()->get();

        return [
            TD::make('system_id', __('admin.products'))
                ->render(function ($product) {
                    return Link::make($product->title)
                        ->route('platform.products.edit', $product->id)
                        ->target('_blank');
                }),
            TD::make('price', __('admin.product.price'))
                ->render(fn ($product) => $product->pivot->price),
            TD::make('price_buy', __('admin.order.products.price_buy'))
                ->render(fn ($product) => $product->pivot->price_buy),
            TD::make('count', __('admin.product.count'))
                ->render(fn ($product) => $product->pivot->count),
            TD::make('unit_system_id', __('admin.product.unit'))
                ->render(function ($product) use ($units)  {
                    $unit = $units->where('system_id', $product->pivot->unit_system_id)->first();
                    return $unit->title ?? '';
                }),
        ];
    }
}
