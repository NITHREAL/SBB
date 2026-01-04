<?php

namespace App\Orchid\Layouts\Shop\ForgottenProduct;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class ForgottenProductListLayout extends Rows
{
    protected string $target = 'forgotten_products';

    protected function fields(): array
    {
        $popularProducts = $this->query->get('forgotten_products');

        return [
            Matrix::make('forgottenProducts')
                ->value($popularProducts)
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
