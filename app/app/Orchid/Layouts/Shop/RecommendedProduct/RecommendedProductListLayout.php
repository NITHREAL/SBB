<?php

namespace App\Orchid\Layouts\Shop\RecommendedProduct;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class RecommendedProductListLayout extends Rows
{
    protected string $target = 'recommendedProducts';

    protected function fields(): array
    {
        $recommendedProducts = $this->query->get('recommendedProducts');

        $recommendedProducts = $recommendedProducts->map(function ($recommendedProduct) {
            $recommendedProduct->category = $recommendedProduct->product
                ->categories
                ->first()?->title ?? 'Нет категории';
        });

        return [
            Matrix::make('recommendedProducts')
                ->value($recommendedProducts)
                ->columns([
                    'Товар'        => 'product_id',
                    'Сортировка'   => 'sort',
                    'Категория'    => 'category'
                ])
                ->fields([
                    'product_id'   => Relation::make()->fromModel(Product::class, 'title', 'id'),
                    'sort'         => Input::make()->type('number'),
                    'category'     => Input::make()->readonly(),
                ])
                ->addRowText('Добавить товар'),
        ];
    }
}

