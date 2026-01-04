<?php

namespace App\Orchid\Screens\Feedback;

use App\Orchid\Layouts\Feedback\FeedbackListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class FeedbackListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Обратная связь';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'feedbacks' => Feedback::filters()
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    /**
     * Button commands
     *
     * @return array
     */
    public function commandBar() : array
    {
        return [
            Link::make(__('admin.export'))
                ->route('export', [
                    'type' => 'feedback'
                ]),
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            FeedbackListLayout::class
        ];
    }
}
