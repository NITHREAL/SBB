<?php

namespace App\Orchid\Layouts\Content\Story;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'stories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {

        return [
            ID::make()->sort(),
            Active::make()->sort(),
            TD::make('title', __('admin.title'))->sort(),
            TD::make('created_at', __('admin.created_at'))->sort()->render(function ($model){
                return $model->created_at->format('d-m-Y H:m:s');
            }),
            TD::make('updated_at', __('admin.updated_at'))->sort()->render(function ($model){
                return $model->updated_at->format('d-m-Y H:m:s');
            }),
            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Edit('platform.stories.edit'),
                (new Actions\Export('story_metadata', [], true))->setTitle('admin.story.export_report'),
            ])
        ];
    }
}
