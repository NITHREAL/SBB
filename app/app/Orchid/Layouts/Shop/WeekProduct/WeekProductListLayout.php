<?php

namespace App\Orchid\Layouts\Shop\WeekProduct;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class WeekProductListLayout extends Rows
{
    protected string $target = 'weekProducts';

    protected function fields(): array
    {
        $weekProducts = $this->query->get('weekProducts');

        return [
            Matrix::make('weekProducts')
                ->value($weekProducts)
                ->columns([
                    'Товар'        => 'product_id',
                    'Сортировка'   => 'sort'
                ])
                ->fields([
                    'product_id'    => Relation::make()->fromModel(Product::class, 'title', 'id'),
                    'sort'          => Input::make()->type('number'),
                ])
                ->addRowText('Добавить товар'),
        ];
    }
}
