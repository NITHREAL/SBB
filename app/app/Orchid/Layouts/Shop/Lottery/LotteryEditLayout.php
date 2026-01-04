<?php

namespace App\Orchid\Layouts\Shop\Lottery;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class LotteryEditLayout extends Rows
{
    protected function fields(): array
    {
        $lottery = $this->query->get('lottery');

        return [
            Input::make('title')
                ->value($lottery->title)
                ->title('Название')
                ->placeholder('Введите название')
                ->required()
                ->horizontal(),
            Quill::make('description')
                ->title('Описание')
                ->value($lottery->description)
                ->placeholder('Введите описание')
                ->horizontal(),
            Input::make('slug')
                ->value($lottery->slug)
                ->title('Слаг')
                ->disabled()
                ->horizontal(),
            Upload::make('images')
                ->value($lottery->image?->id)
                ->title('Изображение экрана розыгрыша')
                ->maxFileSize(1)
                ->maxFiles(1)
                ->acceptedFiles('image/*')
                ->horizontal(),
            Upload::make('imagesMini')
                ->value($lottery->miniImage?->id)
                ->title('Изображение в карусели на главной')
                ->maxFileSize(1)
                ->maxFiles(1)
                ->acceptedFiles('image/*')
                ->horizontal(),
            DateTimer::make('active_from')
                ->title('Срок действия с')
                ->value($lottery->active_from?->format('d-m-Y'))
                ->format('d-m-Y')
                ->placeholder('Выберите дату начала активности')
                ->horizontal(),
            DateTimer::make('active_to')
                ->title('Срок действия по')
                ->value($lottery->active_to?->format('d-m-Y'))
                ->format('d-m-Y')
                ->placeholder('Выберите дату окончания активности')
                ->horizontal(),
            Input::make('sort')
                ->title('Сортировка')
                ->value($lottery->sort)
                ->type('number')
                ->min(1)
                ->step(1)
                ->placeholder('Введите значение сортировки')
                ->horizontal(),
            CheckBox::make('active')
                ->title('Активность')
                ->value($lottery->active)
                ->sendTrueOrFalse()
                ->placeholder('Укажите активность')
                ->horizontal(),
        ];
    }
}
