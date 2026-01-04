<?php

namespace App\Orchid\Layouts\Faq\Faq;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class FaqQuestionEditLayout extends Rows
{
    protected function fields(): array
    {
        $question = $this->query->get('question');

        return [
            Label::make('id')
                ->title(__('admin.id'))
                ->value($question->id)
                ->horizontal(),

            Input::make('title')
                ->title(__('admin.title'))
                ->value($question->title)
                ->required()
                ->horizontal(),

            Input::make('slug')
                ->title('Слаг')
                ->help('Читаемое название для URL, только латиница и тире')
                ->value($question->slug)
                ->horizontal(),

            Quill::make('text')
                ->title('Текст')
                ->value($question->text)
                ->required()
                ->rows(10)
                ->horizontal(),

            Input::make('sort')
                ->title(__('admin.sort'))
                ->value($question->sort)
                ->type('number')
                ->min(0)
                ->help('Чем меньше значение, тем выше в списке')
                ->horizontal(),

            CheckBox::make('active')
                ->title(__('admin.active'))
                ->value($question->active)
                ->sendTrueOrFalse()
                ->horizontal(),
        ];
    }
}
