<?php

namespace Domain\User\Services\Category;

use Domain\User\Helpers\FavoriteCategory\FavoriteCategoryHelper;
use Domain\User\Models\Category\UserFavoriteCategory;
use Illuminate\Support\Collection;

readonly class UserFavoriteCategorySelectService
{
    public function getUserFavoriteCategories(int $userId): Collection
    {
        return UserFavoriteCategory::query()
            ->baseQuery($userId)
            ->wherePeriods(FavoriteCategoryHelper::getActualPeriods())
            ->get();
    }

    public function getCurrentMonthUserFavoriteCategories(int $userId): Collection
    {
        $categories =  UserFavoriteCategory::query()
            ->baseQuery($userId)
            ->wherePeriod(FavoriteCategoryHelper::getCurrentMonthPeriod())
            ->get();

        if ($categories->count() == 0) {
            $categories =  UserFavoriteCategory::query()
                ->baseQuery($userId)
                ->wherePeriod(FavoriteCategoryHelper::getNextMonthPeriod())
                ->get();
        }

        return $categories;
    }
}
