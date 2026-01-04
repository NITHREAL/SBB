<?php

namespace App\Orchid\Layouts\Shop\PromoAction;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class PromoActionEditLayout extends Rows
{
    protected function fields(): array
    {
        $promoAction = $this->query->get('promoAction');

        return [
            Input::make('title')
                ->value($promoAction->title)
                ->title('Название')
                ->placeholder('Введите название')
                ->required()
                ->horizontal(),
            Input::make('short_description')
                ->title('Краткое описание')
                ->value($promoAction->short_description)
                ->placeholder('Введите краткое описание')
                ->horizontal(),
            Quill::make('description')
                ->title('Описание')
                ->value($promoAction->description)
                ->placeholder('Введите описание')
                ->horizontal(),
            Input::make('slug')
                ->value($promoAction->slug)
                ->title('Слаг')
                ->disabled()
                ->horizontal(),
            Upload::make('images')
                ->value($promoAction->image?->id)
                ->title('Изображение экрана промо акции')
                ->maxFileSize(1)
                ->maxFiles(1)
                ->acceptedFiles('image/*')
                ->horizontal(),
            Upload::make('imagesMini')
                ->value($promoAction->miniImage?->id)
                ->title('Изображение в карусели на главной')
                ->maxFileSize(1)
                ->maxFiles(1)
                ->acceptedFiles('image/*')
                ->horizontal(),
            DateTimer::make('active_from')
                ->title('Срок действия с')
                ->value($promoAction->active_from?->format('d-m-Y'))
                ->format('d-m-Y')
                ->placeholder('Выберите дату начала активности')
                ->horizontal(),
            DateTimer::make('active_to')
                ->title('Срок действия по')
                ->value($promoAction->active_to?->format('d-m-Y'))
                ->format('d-m-Y')
                ->placeholder('Выберите дату окончания активности')
                ->horizontal(),
            Input::make('sort')
                ->title('Сортировка')
                ->value($promoAction->sort)
                ->type('number')
                ->min(1)
                ->step(1)
                ->placeholder('Введите значение сортировки')
                ->horizontal(),
            CheckBox::make('active')
                ->title('Активность')
                ->value($promoAction->active)
                ->sendTrueOrFalse()
                ->placeholder('Укажите активность')
                ->horizontal(),
        ];
    }
}
