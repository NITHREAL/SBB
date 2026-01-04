<?php

namespace Domain\User\Jobs\FavoriteCategories;

use Domain\User\Models\User;
use Domain\User\Services\Loyalty\FavoriteCategories\LoyaltyUserFavoriteCategoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetUserFavoriteCategoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly User $user,
        private readonly string $favoriteCategoryLoyaltyId,
    ) {
    }

    public function handle(LoyaltyUserFavoriteCategoryService $loyaltyUserFavoriteCategoryService): void
    {
        $loyaltyUserFavoriteCategoryService->setFavoriteCategoryToUser($this->user, $this->favoriteCategoryLoyaltyId);
    }
}
