<?php

namespace App\Orchid\Layouts\Support;

use Domain\User\Models\User;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

class SupportListLayout extends  Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'user';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {

        return [
            TD::make('first_name', 'Имя')->render(function ($model){
                return $this->makeLink($model, $model->first_name);
            }),
            TD::make('last_name', 'Фамилия')->render(function ($model){
                return $this->makeLink($model, $model->last_name);
            }),
            TD::make('newCount', 'Новых')
                ->sort()
                ->render(function ($model) {
                    return $this->makeLink($model, $model->newCount);
                }),

            TD::make('x', 'Всего')->render(function ($model){
                return $this->makeLink($model, "$model->count");
            }),

        ];
    }

    protected function getNewMessagesCount(User $model): string
    {
        return $model->supportMessages()->whereUnread()->count();
    }

    protected function makeLink($model, $text): Link
    {
        return Link::make($text)
            ->route('platform.support.detail', [
                'user' => $model->id,
            ]);
    }
}
