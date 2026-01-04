<?php

namespace App\Orchid\Layouts\Shop\Promo;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Product;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class PromoProductsLayout extends Rows
{
    protected $target = 'promo.products';

    protected function fields(): array
    {
        return [
            Label::make('')
                ->value('Доступно только для процентных промокодов'),

            Matrix::make('promo.products')
                ->columns([
                    __('admin.products') => 'id'
                ])
                ->fields([
                    'id' => Relation::make()
                        ->fromModel(Product::class, 'title', 'id')
                ])
                ->addRowText(__('admin.promo.products_add')),
        ];
    }
}
