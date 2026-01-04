<?php

namespace App\Orchid\Filters\Review;

use App\Orchid\Filters\Basic\RangeFilter;

class ReviewRatingFilter extends RangeFilter
{
    public $parameters = [
        'rating'
    ];

    public function name(): string
    {
        return __('admin.review.rating');
    }
}
