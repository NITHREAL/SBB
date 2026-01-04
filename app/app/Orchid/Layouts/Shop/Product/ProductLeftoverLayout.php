<?php

namespace App\Orchid\Layouts\Shop\Product;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductLeftoverLayout extends Table
{
    protected $target = 'product.leftovers';

    protected function columns(): array
    {
        return [
            TD::make('store_system_id', 'Магазин')
                ->sort()
                ->render(function ($leftover) {
                    return $leftover->store->title;
                }),
            TD::make('price', 'Цена, руб')
                ->sort()
                ->render(fn ($leftover) => round($leftover->price, 2)),
            TD::make('price_discount', 'Цена со скидкой, руб')
                ->sort()
                ->render(fn ($leftover) => round($leftover->price_discount, 2)),
            TD::make('discount_expires_in', 'Дата окончания скидки')
                ->sort(),
            TD::make('count', 'Количество')
                ->sort()
                ->render(fn ($leftover) => round($leftover->count, 3)),
        ];
    }
}
