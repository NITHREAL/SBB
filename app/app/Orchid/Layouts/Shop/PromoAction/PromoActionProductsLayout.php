<?php

namespace App\Orchid\Layouts\Shop\PromoAction;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class PromoActionProductsLayout extends Rows
{
    protected function fields(): array
    {
        $products = $this->query->get('products');

        return [
            Matrix::make('products')
                ->value($products)
                ->columns([
                    'Товар'        => 'id',
                    'Сортировка'   => 'pivot.sort'
                ])
                ->fields([
                    'id'    => Relation::make()
                        ->fromModel(Product::class, 'title', 'id'),
                    'pivot.sort'  => Input::make()->type('number'),
                ])
                ->addRowText('Добавить товар'),
        ];
    }
}
