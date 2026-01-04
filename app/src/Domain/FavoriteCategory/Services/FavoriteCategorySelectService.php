<?php

namespace Domain\FavoriteCategory\Services;

use Domain\FavoriteCategory\Models\FavoriteCategory;
use Illuminate\Support\Collection;

readonly class FavoriteCategorySelectService
{
    public function getFavoriteCategoriesForPeriod(string $period): Collection
    {
        return FavoriteCategory::query()
            ->wherePeriod($period)
            ->get();
    }
}
