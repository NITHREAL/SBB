<?php

namespace App\Orchid\Layouts\Shop\Group;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class GroupProductsLayout extends Rows
{
    protected $target = 'group.products';

    /**
     * @throws BindingResolutionException
     */
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
                        ->fromModel(Product::class, 'title', 'id')
                        ->chunk(1000),
                    'pivot.sort'  => Input::make()->type('number'),
                ])
                ->addRowText('Добавить товар'),
        ];
    }
}
