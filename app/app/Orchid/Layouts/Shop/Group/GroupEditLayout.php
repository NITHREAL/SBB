<?php

namespace App\Orchid\Layouts\Shop\Group;

use Domain\Audience\Models\Audience;
use Domain\Story\Models\Story;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class GroupEditLayout extends Rows
{
    protected function fields(): array
    {
        $group = $this->query->get('group');

        return [
            Label::make('id')
                ->title(__('admin.id'))
                ->value($group->id)
                ->horizontal(),

            CheckBox::make('active')
                ->title(__('admin.active'))
                ->value($group->active)
                ->sendTrueOrFalse()
                ->horizontal(),

            CheckBox::make('mobile')
                ->title('Доступен в МП')
                ->value($group->mobile)
                ->sendTrueOrFalse()
                ->horizontal(),

            Upload::make('images')
                ->title(__('admin.image'))
                ->value($group->images)
                ->maxFileSize(2)
                ->maxFiles(1)
                ->help('Максимальный размер файла 2 МБ')
                ->acceptedFiles('image/*')
                ->horizontal(),

            Input::make('title')
                ->title(__('admin.title'))
                ->value($group->title)
                ->required()
                ->horizontal(),

            Input::make('slug')
                ->title(__('admin.slug'))
                ->value($group->slug)
                ->horizontal(),

            Input::make('sort')
                ->title(__('admin.sort'))
                ->value($group->sort)
                ->type('number')
                ->min(0)
                ->help('Чем меньше значение, тем выше в списке')
                ->required()
                ->horizontal(),

            Relation::make('audience_id')
                ->fromModel(Audience::class, 'title')
                ->title('Аудитория')
                ->value($group->audience_id)
                ->horizontal(),

            Upload::make('background_image')
                ->value($group->backgroundImage?->id)
                ->title('Обложка подборки')
                ->maxFileSize(1)
                ->maxFiles(1)
                ->help('Максимальный размер файла 2 МБ')
                ->acceptedFiles('image/*')
                ->horizontal(),

            Relation::make('story_id')
                ->fromModel(Story::class, 'title')
                ->applyScope('availableInGroups')
                ->title('Закрепленная история')
                ->value($group->story_id)
                ->horizontal(),
        ];
    }
}
