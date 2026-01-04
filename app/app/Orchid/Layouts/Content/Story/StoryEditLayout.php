<?php

namespace App\Orchid\Layouts\Content\Story;

use App\Orchid\Fields\SmallCropper;
use Domain\Audience\Models\Audience;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class StoryEditLayout extends Rows
{
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     * @throws BindingResolutionException
     */
    protected function fields(): array
    {
        $story = $this->query->get('story');

        return [
            Input::make('title')
                ->title('Название')
                ->placeholder('Название истории')
                ->value($story->title)
                ->required(),
            Relation::make('audienceId')
                ->fromModel(Audience::class, 'title')
                ->value($story->audience_id)
                ->title('Аудитория'),
            SmallCropper::make('imageId')
                ->style(false)
                ->vertical()
                ->value($story->image?->id)
                ->targetId()
                ->title('Иконка 300*300')
                ->width(300)
                ->height(300)
                ->required(),
            CheckBox::make('active')
                ->sendTrueOrFalse()
                ->title('Активность')
                ->value($story->active)
                ->horizontal(),
            CheckBox::make('auto_open')
                ->sendTrueOrFalse()
                ->title('Открывать при запуске МП')
                ->value($story->auto_open)
                ->horizontal(),

            CheckBox::make('available_in_groups')
                ->sendTrueOrFalse()
                ->title('Подборки')
                ->value($story->available_in_groups)
                ->horizontal(),
        ];
    }
}
