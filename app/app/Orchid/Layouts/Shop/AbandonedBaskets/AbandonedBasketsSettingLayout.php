<?php

namespace App\Orchid\Layouts\Shop\AbandonedBaskets;

use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Orchid\Screen\Fields\DateRange;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class AbandonedBasketsSettingLayout extends Rows
{
    protected function fields(): array
    {
        return [
            Input::make('abandoned_over_in_hours')
                ->title('Брошенные более (часов)')
                ->placeholder("")
                ->required()
                ->type('number')
                ->value(24)
                ->min(0),
            Select::make('user_id')
                ->fromModel(User::where('phone', '!=', null), 'phone', 'id')
                ->title(__('admin.user.phone'))
                ->empty('Не выбрано'),
            Select::make('product_id')
                ->fromModel(Product::class, 'title', 'id')
                ->multiple()
                ->title(__('admin.title')." товара")
                ->empty('Не выбрано'),
            DateRange::make('created_at')
                ->title('Дата создания'),
            DateRange::make('updated_at')
                ->title('Дата обновления'),
            Input::make('summ_min')
                ->type('number')
                ->placeholder("")
                ->min(0)
                ->title('Минимальная общая стоимость товаров в корзине'),
            Input::make('summ_max')
                ->type('number')
                ->placeholder("")
                ->min(0)
                ->title('Максимальная общая стоимость товаров в корзине'),
            Select::make('available')
                ->options([
                    1 => 'В наличии',
                ])
                ->title('Наличие товаров к корзине')
                ->empty('Не выбрано')
        ];
    }
}
