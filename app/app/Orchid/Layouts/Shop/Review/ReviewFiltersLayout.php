<?php

namespace App\Orchid\Layouts\Shop\Review;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\CreatedAtFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\Product\ProductFilter;
use App\Orchid\Filters\Review\ReviewRatingFilter;
use App\Orchid\Filters\User\UserFilter;
use Orchid\Screen\Layouts\Selection;

class ReviewFiltersLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            UserFilter::class,
            ProductFilter::class,
            ReviewRatingFilter::class,
            CreatedAtFilter::class,
        ];
    }
}
