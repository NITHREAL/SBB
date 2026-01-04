<?php

namespace App\Orchid\Layouts\Shop\Group;

use App\Orchid\Fields\Matrix;
use Domain\Tag\Models\Tag;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class GroupTagsLayout extends Rows
{
    private const TAGS_MAX_COUNT = 1;

    protected string $target = 'group.tags';

    protected function fields(): array
    {
        $tags = $this->query->get('tags');

        return [
            Matrix::make('tags')
                ->value($tags)
                ->hideFirstColumn()
                ->columns([
                    'ID'                        => 'id',
                    'Текст'                     => 'text',
                    'Цвет'                      => 'color',
                    'Активность'                => 'active',
                    'Принудительно показывать'  => 'show_forced',
                ])
                ->fields([
                    'id'            => Input::make('id')->hidden(),
                    'text'          => Input::make('text')->type('text'),
                    'color'         => Input::make('color')->type('color'),
                    'active'        => CheckBox::make('active')->sendTrueOrFalse(),
                    'show_forced'   => CheckBox::make('show_forced')->sendTrueOrFalse(),
                ])
                ->maxRows(self::TAGS_MAX_COUNT)
                ->addRowText('Добавить тег'),
        ];
    }
}
