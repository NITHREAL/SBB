<?php

namespace App\Orchid\Layouts\Shop\Review;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use Domain\Product\Models\Review;
use Infrastructure\Helpers\PhoneFormatterHelper;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ReviewListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'reviews';

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

            TD::make('', __('admin.user.full_name'))
                ->render(function (Review $review) {
                    if ($review->user_id) {
                        return Link::make($review->user?->fullName ?? PhoneFormatterHelper::format($review->user?->phone))
                            ->route('platform.systems.users.edit', ['user' => $review->user_id]);
                    } elseif ($review->user_name) {
                        return $review->user_name;
                    } elseif ($review->user_phone) {
                        return PhoneFormatterHelper::format($review->user_phone);
                    } else {
                        return 'Анонимный пользователь';
                    }
                }),

            TD::make('product.title', __('admin.review.product'))
                ->sort()
                ->render(function (Review $review) {
                    return Link::make($review->product->title)
                        ->route('platform.products.edit', $review->product);
                }),

            TD::make('rating', __('admin.review.rating'))
                ->sort()
                ->alignCenter(),

            TD::make('text', 'Текст')
                ->sort()
                ->alignCenter(),

            TD::make('created_at', 'Дата')
                ->sort(),

            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Edit('platform.reviews.edit'),
                new Actions\SoftDelete()
            ])
        ];
    }
}
