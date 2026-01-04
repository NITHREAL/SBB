<?php

namespace App\Orchid\Layouts\Shop\Product;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class ProductRelatedProductsLayout extends Rows
{
    protected string $target = 'product.related_products';

    protected function fields(): array
    {
        $product = $this->query->get('product');
        $relatedProducts = $product->relatedProducts()->orderBy('pivot_sort')->get();

        return [
            Matrix::make('related_products')
                ->value($relatedProducts)
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
