<?php

namespace App\Orchid\Layouts\Shop\Promo;

use App\Orchid\Fields\Matrix;
use Domain\Product\Models\Category;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class PromoCategoriesLayout extends Rows
{
    protected string $target = 'promo.categories';

    protected function fields(): array
    {
        return [
            Label::make('')
                ->value('Доступно только для процентных промокодов'),

            Matrix::make('promo.categories')
                ->columns([
                    __('admin.categories') => 'id'
                ])
                ->fields([
                    'id' => Relation::make()
                        ->fromModel(Category::class, 'title', 'id')
                ])
                ->addRowText(__('admin.promo.category_add')),
        ];
    }
}
