<?php


namespace App\Orchid\Layouts\Shop\AbandonedBaskets;

use App\Orchid\Filters\AbandonedBaskets\BasketFilter;
use App\Orchid\Filters\AbandonedBaskets\ProductIdFilter;
use App\Orchid\Filters\AbandonedBaskets\UserIdFilter;
use App\Orchid\Filters\CreatedAtFilter;
use App\Orchid\Filters\UpdatedAtFilter;
use App\Orchid\Filters\User\UserFilter;
use Orchid\Screen\Layouts\Selection;

class AbandonedBasketsFilterLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            UserIdFilter::class,
            UserFilter::class,
            CreatedAtFilter::class,
            UpdatedAtFilter::class,
            ProductIdFilter::class,
            BasketFilter::class
        ];
    }
}
