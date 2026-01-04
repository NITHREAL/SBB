<?php

namespace App\Orchid\Layouts\Faq\FaqCategory;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Layouts\Rows;

class FaqCategoryEditLayout extends Rows
{
    protected function fields(): array
    {
        $category = $this->query->get('category');

        return [
            Label::make('id')
                ->title(__('admin.id'))
                ->value($category->id)
                ->horizontal(),

            Input::make('title')
                ->title(__('admin.title'))
                ->value($category->title)
                ->required()
                ->horizontal(),

            Input::make('slug')
                ->title('Слаг')
                ->value($category->slug)
                ->help('Читаемое название для URL, только латиница и тире')
                ->horizontal(),

            Input::make('sort')
                ->title(__('admin.sort'))
                ->value($category->sort)
                ->type('number')
                ->min(0)
                ->placeholder('Чем меньше значение, тем выше в списке')
                ->horizontal(),

            CheckBox::make('active')
                ->title(__('admin.active'))
                ->value($category->active)
                ->sendTrueOrFalse()
                ->horizontal(),
        ];
    }
}
