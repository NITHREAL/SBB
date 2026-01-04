<?php

namespace App\Orchid\Screens\Shop\Review;

use Domain\Product\Models\Review;
use Domain\Product\Requests\Admin\Review\ReviewRequest;
use Infrastructure\Helpers\PhoneFormatterHelper;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ReviewEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Отзыв на товар';

    protected Review $review;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Review $review): array
    {
        $this->review = $review;

        return [
            'review' => $review,
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make(__('admin.save'))
                ->icon('check')
                ->method('update'),
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        if ($this->review->user_id) {
            $userName = Link::make($this->review->user?->fullName)
                ->title(__('admin.user.full_name'))
                ->horizontal()
                ->route('platform.systems.users.edit', ['user' => $this->review->user_id]);
        } else {
            $userName = "";
            if ($this->review->user_name) {
                $userName .= $this->review->user_name . " ";
            }
            if ($this->review->user_phone) {
                $userName .= PhoneFormatterHelper::format($this->review->user_phone);
            }

            $userName = Label::make('review.userName')
                ->value(empty($userName) ? 'Анонимный пользователь' : trim($userName))
                ->title(__('admin.user.full_name'))
                ->horizontal();
        }

        return [
            Layout::rows([
                Label::make('review.id')
                    ->title(__('admin.id'))
                    ->horizontal(),

                CheckBox::make('review.active')
                    ->title(__('admin.active'))
                    ->placeholder(__('admin.active'))
                    ->sendTrueOrFalse()
                    ->horizontal(),

                $userName,

                Label::make('review.product.title')
                    ->title(__('admin.review.product'))
                    ->horizontal(),

                Label::make('review.rating')
                    ->title(__('admin.review.rating'))
                    ->horizontal(),

                Label::make('review.text')
                    ->title(__('admin.review.text'))
                    ->horizontal(),

                Label::make('review.created_at')
                    ->title(__('admin.created_at'))
                    ->horizontal(),

                Label::make('review.updated_at')
                    ->title(__('admin.updated_at'))
                    ->horizontal(),
            ]),
        ];
    }

    public function update(Review $review, ReviewRequest $request)
    {
        $data = $request->validated()['review'];

        $review->update($data);

        Toast::success(__('admin.toasts.updated'));

        return redirect()->route('platform.reviews.list');
    }
}
