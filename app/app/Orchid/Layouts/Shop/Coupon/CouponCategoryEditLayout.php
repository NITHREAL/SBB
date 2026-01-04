<?php

namespace App\Orchid\Layouts\Shop\Coupon;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class CouponCategoryEditLayout extends Rows
{
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $categoryCoupon = $this->query->get('category');

        return [
            Label::make('system_id')
                ->title('Системный идентификатор')
                ->value($categoryCoupon->system_id),
            Input::make('title')
                ->value($categoryCoupon->title)
                ->title('Название')
                ->placeholder('Введите название')
                ->required()
                ->horizontal(),
            Quill::make('description')
                ->title('Описание')
                ->value($categoryCoupon->description)
                ->placeholder('Введите описание')
                ->horizontal(),
            Textarea::make('purchase_terms')
                ->title('Условия покупки')
                ->value($categoryCoupon->purchase_terms)
                ->placeholder('Введите условия покупки')
                ->horizontal(),
            Input::make('price')
                ->title('Цена, р')
                ->value($categoryCoupon->price)
                ->type('number')
                ->min(0)
                ->step(1)
                ->placeholder('Введите цену')
                ->horizontal(),
            Upload::make('mainImage')
                ->value($categoryCoupon->mainImage?->id)
                ->title('Заглавное изображение')
                ->maxFileSize(1)
                ->maxFiles(1)
                ->acceptedFiles('image/*')
                ->horizontal(),
            Upload::make('images')
                ->title('Изображения')
                ->value($categoryCoupon->images->pluck('id')->toArray())
                ->acceptedFiles('image/*')
                ->horizontal(),
            Input::make('sort')
                ->title('Сортировка')
                ->value($categoryCoupon->sort)
                ->type('number')
                ->min(1)
                ->step(1)
                ->placeholder('Введите значение сортировки')
                ->horizontal(),
            CheckBox::make('active')
                ->title('Активность')
                ->value($categoryCoupon->active)
                ->sendTrueOrFalse()
                ->placeholder('Укажите активность')
                ->horizontal(),
        ];
    }
}
