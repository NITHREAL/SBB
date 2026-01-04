<?php

namespace Domain\User\Services\Store;

use Domain\Store\Models\Store;
use Domain\User\Exceptions\UserStoreException;
use Domain\User\Models\User;
use Domain\User\Models\UserStore;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserStoreService
{
    public function getUserStores(User $user): Collection
    {
        return Store::query()
            ->baseQuery()
            ->byUserCollection($user->id)
            ->get();
    }

    public function getUserStoresByCity(int $userId, int $cityId): Collection
    {
        return Store::query()
            ->baseQuery()
            ->byUserCollection($userId)
            ->byCityCollection($cityId)
            ->orderByDesc('favorite_stores.updated_at')
            ->get();
    }

    /**
     * @throws Exception
     */
    public function addStore(User $user, int $storeId): Store
    {
        $store = Store::query()
            ->baseQuery()
            ->where('stores.id', $storeId)
            ->whereActive()
            ->first();

        if (empty($store)) {
            throw new Exception("Магазин с ID [{$storeId}] не найден или недоступен", 400);
        }

        if ($this->isStoreInFavoritesExists($user, $storeId)) {
            throw new Exception("Магазин с ID [{$storeId}] уже есть в списке избранных", 400);
        }

        $userStore = new UserStore();

        $userStore->user()->associate($user);
        $userStore->store()->associate($store);

        $userStore->save();

        return $store;
    }

    public function removeStore(User $user, $storeId): bool
    {
        return UserStore::query()
            ->where('user_id', $user->id)
            ->where('store_id', $storeId)
            ->delete();
    }

    public function setStoreChosen(int $storeId, int $userId): void
    {
        try {
            DB::beginTransaction();

            $currentChosenStore = UserStore::query()
                ->whereUser($userId)
                ->whereChosen()
                ->first();

            if (empty($currentChosenStore) || $currentChosenStore->store_id !== $storeId) {
                UserStore::query()->whereUser($userId)->whereStore($storeId)->update(['chosen' => true]);

                if ($currentChosenStore) {
                    $currentChosenStore->chosen = false;
                    $currentChosenStore->save();
                }
            }

            DB::commit();
        } catch (UserStoreException $exception) {
            DB::rollBack();

            $message = sprintf(
                'Ошибка во время изменения состояния "выбранный" у магазина [%s] пользователя [%s]. Ошибка - [%s]',
                $storeId,
                $userId,
                $exception->getMessage(),
            );

            Log::error($message);
        }
    }

    private function isStoreInFavoritesExists(User $user, $storeId): bool
    {
        $userStores = $this->getUserStores($user);

        return $userStores->where('id', $storeId)->isNotEmpty();
    }
}
