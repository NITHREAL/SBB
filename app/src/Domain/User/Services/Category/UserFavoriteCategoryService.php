<?php

namespace Domain\User\Services\Category;

use Domain\Faq\Helpers\FaqCategoryHelper;
use Domain\Faq\Services\Faq\FaqSelectionService;
use Domain\FavoriteCategory\Models\FavoriteCategory;
use Domain\FavoriteCategory\Services\FavoriteCategorySelectService;
use Domain\User\DTO\Category\FavoriteCategoriesUpdateDTO;
use Domain\User\Exceptions\FavoriteCategoryException;
use Domain\User\Helpers\FavoriteCategory\FavoriteCategoryHelper;
use Domain\User\Jobs\FavoriteCategories\SetUserFavoriteCategoryJob;
use Domain\User\Models\User;
use Illuminate\Support\Collection;

readonly class UserFavoriteCategoryService
{
    public function __construct(
        private UserFavoriteCategoryChangeService $userFavoriteCategoryChangeService,
        private UserFavoriteCategorySelectService $userFavoriteCategorySelectService,
        private FavoriteCategorySelectService $favoriteCategorySelectService,
        private FaqSelectionService               $faqSelectionService,
    ) {
    }

    /**
     * Получение данных для раздела "Любимые категории" пользователя
     *
     * @param int $userId
     * @return array
     */
    public function getFavoriteCategoriesData(int $userId): array
    {
        $faq = $this->faqSelectionService->getFaqBySlug(FaqCategoryHelper::FAVORITE_CATEGORIES_FAQ_SLUG);
        $categories = $this->userFavoriteCategorySelectService->getUserFavoriteCategories($userId);

        $categoriesData = $categories
            ->groupBy('period')
            ->map(function ($categories, $period) {
                return [
                    'period'        => $period,
                    'categories'    => $categories,
                ];
            })
            ->values()
            ->toArray();

        return [
            'categoriesData'    => $categoriesData,
            'faq'               => $faq,
            'availablePeriod'   => $this->getAvailablePeriodForChoose($categories),
        ];
    }

    /**
     * Получение данных для выбора любимых категорий пользователя
     *
     * @param User $user
     * @return array
     * @throws FavoriteCategoryException
     */
    public function getAvailableCategoriesData(User $user): array
    {
        $period = FavoriteCategoryHelper::getCurrentAvailablePeriod();

        if (empty($period)) {
            throw new FavoriteCategoryException(
                'Ошибка при изменении списка любимых категорий пользователя. Нет доступных периодов для выбора'
            );
        }

        $this->checkChangeAvailability($user, $period);

        $categories = $this->favoriteCategorySelectService->getFavoriteCategoriesForPeriod($period);

        return [
            'categories'    => $categories,
            'period'        => $period,
        ];
    }

    /**
     * @throws FavoriteCategoryException
     */
    public function chooseFavoriteCategories(FavoriteCategoriesUpdateDTO $favoriteCategoryDTO): Collection
    {
        $user = User::findOrFail($favoriteCategoryDTO->getUserId());

        $this->checkChangeAvailability($user, $favoriteCategoryDTO->getPeriod());

        $this->userFavoriteCategoryChangeService->updateUserFavoriteCategories($user, $favoriteCategoryDTO);

        $this->updateFavoriteCategoriesInLoyalty($favoriteCategoryDTO, $user);

        return $this->userFavoriteCategorySelectService->getUserFavoriteCategories($favoriteCategoryDTO->getUserId());
    }

    /**
     * Определение доступности выбора любимых категорий пользователя для указанного периода
     *
     * @param User $user
     * @param string $period
     * @return void
     * @throws FavoriteCategoryException
     */
    private function checkChangeAvailability(User $user, string $period): void
    {
        if (FavoriteCategoryHelper::isPeriodAvailable($period) === false) {
            throw new FavoriteCategoryException(
                sprintf(
                    'Ошибка при изменении списка любимых категорий пользователя. Изменение для периода [%s] недоступно',
                    $period,
                )
            );
        }

        if ($user->categories()->wherePivot('period', $period)->count()) {
            throw new FavoriteCategoryException(
                sprintf(
                    'Ошибка при изменении списка любимых категорий пользователя. Для периода [%s] уже выбраны категории',
                    $period,
                ),
            );
        }
    }

    /**
     * Определения доступного периода для выбора любимых категорий
     *
     * @param Collection $choosedCategories
     * @return string|null
     */
    private function getAvailablePeriodForChoose(Collection $choosedCategories): ?string
    {
        $dateAvailablePeriod = FavoriteCategoryHelper::getCurrentAvailablePeriod();

        return $dateAvailablePeriod && $choosedCategories->where('period', $dateAvailablePeriod)->isEmpty()
            ? $dateAvailablePeriod
            : null;
    }

    private function updateFavoriteCategoriesInLoyalty(FavoriteCategoriesUpdateDTO $categoriesUpdateDTO, User $user): void
    {
        $categoryLoyaltyIds = FavoriteCategory::query()
            ->whereIn('id', $categoriesUpdateDTO->getCategoryIds())
            ->pluck('loyalty_id');

        foreach ($categoryLoyaltyIds as $categoryLoyaltyId) {
            SetUserFavoriteCategoryJob::dispatch($user, $categoryLoyaltyId)->delay(3);
        }
    }
}
