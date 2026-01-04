<?php

namespace App\Http\Controllers\Api\V1\User\Category;

use App\Http\Controllers\Controller;
use Domain\User\DTO\Category\FavoriteCategoriesUpdateDTO;
use Domain\User\Exceptions\FavoriteCategoryException;
use Domain\User\Models\User;
use Domain\User\Requests\Category\FavoriteCategoryCreateRequest;
use Domain\User\Resources\Category\AvailableCategoriesDataResource;
use Domain\User\Resources\Category\FavoriteCategoriesDataResource;
use Domain\User\Resources\Category\FavoriteCategoryResource;
use Domain\User\Services\Category\UserFavoriteCategorySelectService;
use Domain\User\Services\Category\UserFavoriteCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class FavoriteCategoryController extends Controller
{
    public function index(UserFavoriteCategoryService $favoriteCategoryService): JsonResponse
    {
        $data = $favoriteCategoryService->getFavoriteCategoriesData(Auth::id());

        return ApiResponse::handle(
            FavoriteCategoriesDataResource::make($data),
        );
    }

    /**
     * @throws FavoriteCategoryException
     */
    public function availableCategories(UserFavoriteCategoryService $favoriteCategoryService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $favoriteCategoryService->getAvailableCategoriesData($user);

        return ApiResponse::handle(
            AvailableCategoriesDataResource::make($data),
        );
    }

    public function currentCategories(UserFavoriteCategorySelectService $favoriteCategorySelectService): JsonResponse
    {
        $categories = $favoriteCategorySelectService->getCurrentMonthUserFavoriteCategories(Auth::id());

        return ApiResponse::handle(
            FavoriteCategoryResource::collection($categories),
        );
    }

    /**
     * @throws FavoriteCategoryException
     */
    public function store(
        FavoriteCategoryCreateRequest $request,
        UserFavoriteCategoryService   $favoriteCategoryService,
    ): JsonResponse {
        $data = array_merge(
            $request->validated(),
            // Используется ID, а не весь объект пользователя, чтобы при необходимости полученную DTO можно было использовать
            // и в случае, когда привязку нужно осуществить только по ID
            ['userId' => Auth::id()],
        );

        $dto = FavoriteCategoriesUpdateDTO::make($data);

        $favoriteCategories = $favoriteCategoryService->chooseFavoriteCategories($dto);

        return ApiResponse::handle(
            FavoriteCategoryResource::collection($favoriteCategories)
        );
    }
}
