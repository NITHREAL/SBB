<?php

namespace App\Orchid\Layouts\Content\Story;

use Domain\Image\Models\Attachment;
use Domain\Story\Enums\StoryPageTypeEnum;
use Domain\Story\Models\StoryPage;
use Orchid\Screen\Layouts\Table;
use App\Orchid\Core\TD;
use Orchid\Screen\Actions\Link;

class StoryPageListTableLayout extends Table
{

    public $target = 'story.pages';


    public function columns(): array
    {
        return [
            TD::make('page.position', __('admin.story.page.position'))->render(function ($model){
                return $model->position;
            })->alignCenter()->width(20),

            TD::make('page.title', __('admin.story.page.title'))->render(function ($model){
                return $this->makeLink($model, 'title')->width(100);
            }),

            TD::make('page.text', __('admin.story.page.text'))->render(function ($model){
                return $this->makeLink($model, 'text');
            })->width(400),

            TD::make('page.type', __('admin.story.page.type'))->render(function ($model){
                return $this->makeLink($model, '', (new StoryPageTypeEnum($model->type))->label);
            }),

            TD::make('page.timer', __('admin.story.page.timer'))->render(function ($model){
                return $model->timer;
            })->alignCenter()->width(20),

            TD::make()
                ->render(function ($model){
                return '<img style="width: 100px" src="'.Attachment::find($model->image)?->url().'">';
            })
        ];
    }

    protected function makeLink(StoryPage $model, string $attr, ?string $text = null): Link
    {
        return Link::make($text ?? $model->{$attr})
            ->route('platform.stories.pages.edit', [
                'id' => $model->id,
                'parent_id' => $model->story->id,
            ]);
    }

}
