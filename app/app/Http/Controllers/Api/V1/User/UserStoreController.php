<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Domain\User\Models\User;
use Domain\User\Resources\Store\UserStoreResource;
use Domain\User\Resources\Store\UserStoresResource;
use Domain\User\Services\Store\UserStoreService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class UserStoreController extends Controller
{
    public function index(UserStoreService $userStoreService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $storesData = $userStoreService->getUserStores($user);

        return ApiResponse::handle(
            UserStoresResource::make([
                'userId'    => $user->id,
                'stores'    => $storesData,
            ]),
        );
    }

    /**
     * @throws Exception
     */
    public function add(
        int $storeId,
        UserStoreService $userStoreService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $addedStore = $userStoreService->addStore(
            $user,
            $storeId,
        );

        return ApiResponse::handle(
            UserStoreResource::make([
                'userId'    => $user->id,
                'store'     => $addedStore,
            ]),
        );
    }

    public function delete(
        int $storeId,
        UserStoreService $userStoreService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $userStoreService->removeStore(
            $user,
            $storeId,
        );

        return ApiResponse::handleNoContent();
    }
}
