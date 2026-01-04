<?php

namespace App\Orchid\Layouts\Feedback;

use App\Models\Feedback;
use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FeedbackListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'feedbacks';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make(),
            TD::make('name', __('admin.feedback.name'))
                ->filter(TD::FILTER_TEXT)
                ->sort()
                ->render(fn (Feedback $feedback) => substr($feedback->name, 0, 50)),

            TD::make('phone', __('admin.feedback.phone'))
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::make('city', __('admin.feedback.city'))
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            DateTime::createdAt(),
            DateTime::updatedAt(),

            TD::make()->actions([
                new Actions\Show('platform.feedbacks.show'),
            ])
        ];
    }
}
