<?php

namespace App\Orchid\Layouts\Support;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;


class ChatInputLayout extends Rows
{
    protected function fields(): array
    {

        return [
            TextArea::make('answer')
                ->title('Написать сообщение')
                ->placeholder('Введите текст сообщения')
                ->style('max-width: none'),
            Input::make('user_id')
                ->value($this->query->get('user')->id)
                ->hidden(),
            Button::make('Отправить')
                ->value('12')
                ->action('send')
                ->style(false)
                ->method('post')
                ->class('btn btn-primary')
        ];
    }
}
