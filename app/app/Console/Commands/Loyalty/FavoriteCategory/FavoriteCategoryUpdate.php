<?php

namespace App\Console\Commands\Loyalty\FavoriteCategory;

use Domain\FavoriteCategory\Services\FavoriteCategoryService;
use Illuminate\Console\Command;
use Infrastructure\Services\Loyalty\Exceptions\LoyaltyException;

class FavoriteCategoryUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:favorite-categories:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление списка любимых категорий';

    /**
     * @throws LoyaltyException
     */
    public function handle(FavoriteCategoryService $favoriteCategoryService): void
    {
        $favoriteCategoryService->updateFavoriteCategories();
    }
}
