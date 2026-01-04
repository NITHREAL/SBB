<?php

namespace App\Orchid\Screens\Feedback;

use App\Models\Feedback;
use App\Orchid\Helpers\Sight\DateTimeSight;
use App\Orchid\Helpers\Sight\IdSight;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class FeedbackShowScreen extends Screen
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
    public function query(Feedback $feedback): array
    {
        return [
            'feedback' => $feedback,
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
            Layout::legend('feedback', [
                IdSight::make(),
                Sight::make('name', __('admin.feedback.name')),
                Sight::make('phone', __('admin.feedback.phone')),
                Sight::make('city', __('admin.feedback.city')),
                Sight::make('comment', __('admin.feedback.comment')),
                DateTimeSight::createdAt(),
                DateTimeSight::updatedAt(),
            ]),
        ];
    }
}
