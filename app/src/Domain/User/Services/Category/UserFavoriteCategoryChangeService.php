<?php

namespace Domain\User\Services\Category;

use Domain\User\DTO\Category\FavoriteCategoriesUpdateDTO;
use Domain\User\Models\User;

readonly class UserFavoriteCategoryChangeService
{
    public function updateUserFavoriteCategories(User $user, FavoriteCategoriesUpdateDTO $favoriteCategoryDTO): void
    {
        $categoryIds = $favoriteCategoryDTO->getCategoryIds();
        $period = $favoriteCategoryDTO->getPeriod();

        $categories = [];

        foreach ($categoryIds as $categoryId) {
            $categories[] = [
                'category_id' => $categoryId,
                'period'      => $period,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ];
        }

        // Удаляем предыдущие выбранные категории за этот период и добавляем новые
        $user->categories()->wherePivot('period', $period)->detach();
        $user->categories()->attach($categories);
    }
}
